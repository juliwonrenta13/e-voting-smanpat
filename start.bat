@echo off
title Menjalankan E-Voting SMAN 4
color 0A

echo ========================================================
echo         MENJALANKAN APLIKASI E-VOTING SMAN 4
echo ========================================================
echo.

echo Menghubungkan runtime Laragon PHP ^& MySQL...
set PATH=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64;C:\laragon\bin\composer;C:\laragon\bin\nodejs\node-v18;C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin;%PATH%

echo.
echo Mendeteksi IP Address jaringan lokal...

:: Ambil IP address lokal (bukan 127.0.0.1)
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /i "IPv4" ^| findstr /v "127.0.0.1"') do (
    set "LOCAL_IP=%%a"
    goto :found_ip
)
:found_ip
:: Hapus spasi di depan
set LOCAL_IP=%LOCAL_IP: =%

echo IP Address Server: %LOCAL_IP%
echo.

:: Update APP_URL di .env agar asset/link berfungsi dari jaringan lokal
powershell -Command "(Get-Content .env) -replace 'APP_URL=.*', 'APP_URL=http://%LOCAL_IP%:8000' | Set-Content .env"

echo ========================================================
echo.
echo  Akses dari komputer LAIN di jaringan yang sama:
echo.
echo  - Bilik Suara Siswa : http://%LOCAL_IP%:8000
echo  - Panel Admin       : http://%LOCAL_IP%:8000/admin/login
echo.
echo  Akses dari komputer INI (localhost):
echo  - Bilik Suara Siswa : http://127.0.0.1:8000
echo  - Panel Admin       : http://127.0.0.1:8000/admin/login
echo.
echo  CATATAN: Pastikan semua komputer terhubung ke WiFi/LAN
echo           yang sama dengan komputer server ini!
echo.
echo  Tekan CTRL+C untuk menghentikan server.
echo ========================================================
echo.

php artisan serve --host=0.0.0.0 --port=8000
pause
