<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\DB;
use App\Core\Csrf;

class AuthController extends Controller {

    public function login() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!empty($_SESSION['uid'])) { header('Location: /'); exit; }
        $success = $_GET['ok'] ?? null; // opsiyonel
        $this->view('auth/gp_login', ['success' => $success ? 'Giriş yapabilirsiniz.' : null], 'layouts/auth');
    }

    public function loginPost() {
        if (!\App\Core\Csrf::check($_POST['_token'] ?? '')) { http_response_code(419); echo 'CSRF token mismatch'; return; }
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = \App\Core\DB::conn()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION['uid'] = (int)$user['id'];
            $_SESSION['name'] = $user['name'];
            $this->redirect('/');
        } else {
            $this->view('auth/gp_login', ['error' => 'Geçersiz kullanıcı bilgileri'], 'layouts/auth');
        }
    }

    public function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        session_destroy();
        $this->redirect('/login');
    }

    /* ========== REGISTER ========== */
    public function register() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!empty($_SESSION['uid'])) { header('Location: /'); exit; }
        $this->view('auth/register', [], 'layouts/auth');
    }

    public function registerPost() {
        if (!Csrf::check($_POST['_token'] ?? '')) { http_response_code(419); echo 'CSRF token mismatch'; return; }
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass  = (string)($_POST['password'] ?? '');
        $pass2 = (string)($_POST['password_confirm'] ?? '');

        $errors = [];
        if ($name === '' || mb_strlen($name) < 2) $errors[] = 'Ad Soyad en az 2 karakter olmalı';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli bir e-posta giriniz';
        if (mb_strlen($pass) < 8) $errors[] = 'Şifre en az 8 karakter olmalı';
        if ($pass !== $pass2) $errors[] = 'Şifreler eşleşmiyor';

        // E-posta benzersiz?
        $stmt = DB::conn()->prepare('SELECT COUNT(1) c FROM users WHERE email = :e');
        $stmt->execute(['e' => $email]);
        $exists = (int)$stmt->fetchColumn() > 0;
        if ($exists) $errors[] = 'Bu e-posta zaten kayıtlı';

        if ($errors) {
            $this->view('auth/register', ['error_list' => $errors, 'old' => compact('name','email')], 'layouts/auth');
            return;
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = DB::conn()->prepare('INSERT INTO users (name,email,password_hash,role) VALUES (:n,:e,:h,"admin")');
        $stmt->execute(['n'=>$name, 'e'=>$email, 'h'=>$hash]);

        // Başarılı → login sayfasını başarı mesajıyla göster
        $this->view('auth/gp_login', ['success' => 'Kayıt tamamlandı. Giriş yapabilirsiniz.'], 'layouts/auth');
    }

    /* ========== PASSWORD FORGOT / RESET ========== */
    public function forgot() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!empty($_SESSION['uid'])) { header('Location: /'); exit; }
        $this->view('auth/forgot', [], 'layouts/auth');
    }

    public function forgotPost() {
        if (!Csrf::check($_POST['_token'] ?? '')) { http_response_code(419); echo 'CSRF token mismatch'; return; }
        $email = trim($_POST['email'] ?? '');

        // Kullanıcı var mı (bilgi sızdırmamak için sonucu her zaman success göstereceğiz)
        $stmt = DB::conn()->prepare('SELECT id FROM users WHERE email = :e LIMIT 1');
        $stmt->execute(['e' => $email]);
        $user = $stmt->fetch();

        $dev_link = null;
        if ($user) {
            $raw = bin2hex(random_bytes(32));
            $hash = hash('sha256', $raw);
            $expires = (new \DateTime('+30 minutes'))->format('Y-m-d H:i:s');

            // Eski tokenleri iptal et
            DB::conn()->prepare('DELETE FROM password_resets WHERE user_id = :uid OR created_at < DATE_SUB(NOW(), INTERVAL 2 DAY)')->execute(['uid' => $user['id']]);

            $ins = DB::conn()->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at, created_at) VALUES (:uid, :th, :exp, NOW())');
            $ins->execute(['uid'=>$user['id'], 'th'=>$hash, 'exp'=>$expires]);

            $dev_link = '/password/reset?token=' . $raw;
        }

        // E-posta gönderimini burada SMTP ile ekleyebilirsin (PHPMailer vb.).
        // Şimdilik geliştirici kolaylığı: linki sayfada gösteriyoruz.
        $this->view('auth/forgot', ['success' => 'Eğer e-posta kayıtlıysa, sıfırlama bağlantısı oluşturuldu.', 'dev_link' => $dev_link], 'layouts/auth');
    }

    public function reset() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (!empty($_SESSION['uid'])) { header('Location: /'); exit; }

        $token = (string)($_GET['token'] ?? '');
        if ($token === '') { http_response_code(400); echo 'Eksik token'; return; }
        $this->view('auth/reset', ['token' => $token], 'layouts/auth');
    }

    public function resetPost() {
        if (!Csrf::check($_POST['_token'] ?? '')) { http_response_code(419); echo 'CSRF token mismatch'; return; }
        $token = (string)($_POST['token'] ?? '');
        $pass  = (string)($_POST['password'] ?? '');
        $pass2 = (string)($_POST['password_confirm'] ?? '');

        $errors = [];
        if ($token === '') $errors[] = 'Eksik istek';
        if (mb_strlen($pass) < 8) $errors[] = 'Şifre en az 8 karakter olmalı';
        if ($pass !== $pass2) $errors[] = 'Şifreler eşleşmiyor';

        if ($errors) {
            $this->view('auth/reset', ['token' => $token, 'error_list' => $errors], 'layouts/auth');
            return;
        }

        $hash = hash('sha256', $token);
        $stmt = DB::conn()->prepare('SELECT pr.*, u.id as uid FROM password_resets pr JOIN users u ON u.id = pr.user_id WHERE pr.token_hash = :h AND pr.used_at IS NULL AND pr.expires_at > NOW() LIMIT 1');
        $stmt->execute(['h' => $hash]);
        $row = $stmt->fetch();

        if (!$row) {
            $this->view('auth/reset', ['token' => $token, 'error_list' => ['Geçersiz veya süresi dolmuş bağlantı']], 'layouts/auth');
            return;
        }

        DB::conn()->beginTransaction();
        try {
            $upd = DB::conn()->prepare('UPDATE users SET password_hash = :ph WHERE id = :uid');
            $upd->execute(['ph' => password_hash($pass, PASSWORD_DEFAULT), 'uid' => $row['uid']]);

            $mark = DB::conn()->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = :id');
            $mark->execute(['id' => $row['id']]);

            DB::conn()->commit();
        } catch (\Throwable $e) {
            DB::conn()->rollBack();
            $this->view('auth/reset', ['token' => $token, 'error_list' => ['Bir hata oluştu']], 'layouts/auth');
            return;
        }

        $this->view('auth/gp_login', ['success' => 'Şifreniz güncellendi. Giriş yapabilirsiniz.'], 'layouts/auth');
    }
}
