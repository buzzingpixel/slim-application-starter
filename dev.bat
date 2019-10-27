@echo off

set cmd=%1
set allArgs=%*
for /f "tokens=1,* delims= " %%a in ("%*") do set allArgsExceptFirst=%%b
set secondArg=%2
set valid=false

:: If no command provided, list commands
if "%cmd%" == "" (
    set valid=true
    echo The following commands are available:
    echo   .\dev up
    echo   .\dev down
    echo   .\dev cli [args]
    echo   .\dev composer [args]
    echo   .\dev phpstan
    echo   .\dev phpunit [args]
    echo   .\dev login [args]
)

if "%cmd%" == "up" (
    docker-compose -f docker-compose.yml -p buzzingpixel up -d
    exit /b 0
)

if "%cmd%" == "down" (
    docker-compose -f docker-compose.yml -p buzzingpixel down
    exit /b 0
)

if "%cmd%" == "cli" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-php bash -c "php cli %allArgsExceptFirst%"
    exit /b 0
)

if "%cmd%" == "composer" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-php bash -c "%allArgs%"
    exit /b 0
)

if "%cmd%" == "psalm" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-php bash -c "chmod +x /opt/project/vendor/bin/psalm && /opt/project/vendor/bin/psalm"
    exit /b 0
)

if "%cmd%" == "phpstan" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-php bash -c "chmod +x /opt/project/vendor/bin/phpstan && /opt/project/vendor/bin/phpstan analyse config public/index.php src tests cli"
    exit /b 0
)

if "%cmd%" == "phpunit" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-php bash -c "chmod +x /opt/project/vendor/bin/phpunit && /opt/project/vendor/bin/phpunit --configuration /opt/project/phpunit.xml %allArgsExceptFirst%"
    exit /b 0
)

if "%cmd%" == "login" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-%secondArg% bash
    exit /b 0
)

echo Specified command not found
exit /b 1
