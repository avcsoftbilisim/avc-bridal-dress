# scripts\fetch_coreui.ps1 — CoreUI'yi yerel klasöre indir/çıkar (bir kez internetle)
$ErrorActionPreference = "Stop"

# Proje kökünü ve hedef klasörleri hesapla
$root     = Split-Path -Parent $PSScriptRoot
$vendor   = Join-Path $root "public\assets\vendor\coreui"
$iconsDir = Join-Path $vendor "icons"
$fontsDir = Join-Path $vendor "fonts"
$tmp      = Join-Path $root  "scripts\tmp"

New-Item -ItemType Directory -Force -Path $vendor,$iconsDir,$fontsDir,$tmp | Out-Null

# Sürümler
$coreuiVer = "5.1.0"
$iconsVer  = "3.0.0"

# NPM .tgz arşiv linkleri
$coreuiTgz = "https://registry.npmjs.org/@coreui/coreui/-/coreui-$coreuiVer.tgz"
$iconsTgz  = "https://registry.npmjs.org/@coreui/icons/-/icons-$iconsVer.tgz"

# İndir
Invoke-WebRequest $coreuiTgz -OutFile "$tmp\coreui.tgz"
Invoke-WebRequest $iconsTgz  -OutFile "$tmp\icons.tgz"

# Çıkar (Windows'ta 'tar' mevcut)
tar -xzf "$tmp\coreui.tgz" -C "$tmp"
Rename-Item -Force "$tmp\package" "$tmp\coreui"

tar -xzf "$tmp\icons.tgz" -C "$tmp"
Rename-Item -Force "$tmp\package" "$tmp\icons"

# Gerekli dosyaları kopyala
Copy-Item -Force "$tmp\coreui\dist\css\coreui.min.css"       "$vendor\coreui.min.css"
Copy-Item -Force "$tmp\coreui\dist\js\coreui.bundle.min.js"  "$vendor\coreui.bundle.min.js"

Copy-Item -Force "$tmp\icons\css\free.min.css"               "$iconsDir\free.min.css"
Copy-Item -Force "$tmp\icons\fonts\*"                         "$fontsDir\"

Write-Host "OK: Dosyalar kopyalandı:"
Write-Host " - $vendor\coreui.min.css"
Write-Host " - $vendor\coreui.bundle.min.js"
Write-Host " - $iconsDir\free.min.css"
Write-Host " - $fontsDir\(free.*)"
Write-Host "Artık uygulama tamamen offline çalışabilir."
