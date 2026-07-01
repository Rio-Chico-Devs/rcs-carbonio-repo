@echo off
setlocal enabledelayedexpansion

:: Cartella sorgente con i file da comprimere
set "source_folder=C:\Users\bruno\OneDrive\Desktop\IMG CONVERTER\file batch\filewebp"

:: Cartella destinazione = dove si trova il batch
set "dest_folder=%~dp0"
if "%dest_folder:~-1%"=="\" set "dest_folder=%dest_folder:~0,-1%"

echo ====================================
echo COMPRESSIONE WEBP
echo ====================================
echo Sorgente: %source_folder%
echo Destinazione: %dest_folder%
echo ====================================
echo.

:: Conta i file processati
set count=0

:: Processa ogni file WEBP dalla cartella sorgente
for %%f in ("%source_folder%\*.webp") do (
    magick "%%f" -quality 40 -define webp:method=6 -define webp:target-size=100000 "%dest_folder%\%%~nf.webp"
    set /a count+=1
    echo [!count!] Compresso: %%~nxf
)

echo.
if %count%==0 (
    echo ATTENZIONE: Nessun file .webp trovato in %source_folder%
) else (
    echo ====================================
    echo Compressione completata!
    echo File processati: %count%
    echo Salvati in: %dest_folder%
    echo ====================================
)
echo.
pause
