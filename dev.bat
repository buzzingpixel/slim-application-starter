@echo off

set composerDockerImage=composer:1.9.0
set cypressDockerImage=cypress/included:3.5.0
set nodeDockerImage=node:12.12.0

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
    echo   .\dev provision
    echo   .\dev login [args]
    echo   .\dev cli [args]
    echo   .\dev yarn [args]
    echo   .\dev composer [args]
    echo   .\dev phpcs [args]
    echo   .\dev psalm
    echo   .\dev phpstan
    echo   .\dev phpunit [args]
    echo   .\dev eslint
    echo   .\dev cypress
    echo   .\dev cypress-interactive
    exit /b 0
)

if "%cmd%" == "up" (
    docker-compose build
    docker-compose -f docker-compose.yml -p buzzingpixel up -d
    exit /b 0
)

if "%cmd%" == "down" (
    docker kill buzzingpixel-utility
    docker-compose -f docker-compose.yml -p buzzingpixel down
    exit /b 0
)

if "%cmd%" == "provision" (
    docker build -t buzzingpixel:php-dev docker/php-dev
    docker run -it -v %cd%:/app -v buzzingpixel_composer-home-volume:/composer-home-volume --env COMPOSER_HOME=/composer-home-volume -w /app %composerDockerImage% bash -c "composer install"
    docker run -it -v %cd%:/app -v buzzingpixel_node-modules-volume:/app/node_modules -v buzzingpixel_yarn-cache-volume:/usr/local/share/.cache/yarn -w /app %nodeDockerImage% bash -c "yarn"
    docker run -it -v %cd%:/app -v buzzingpixel_node-modules-volume:/app/node_modules -v buzzingpixel_yarn-cache-volume:/usr/local/share/.cache/yarn -w /app %nodeDockerImage% bash -c "yarn run fab --build-only"
    cd platform
    call yarn
    cd ..
    exit /b 0
)

if "%cmd%" == "login" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-%secondArg% bash
    exit /b 0
)

if "%cmd%" == "cli" (
    docker exec -it --user root --workdir /opt/project buzzingpixel-php bash -c "php cli %allArgsExceptFirst%"
    exit /b 0
)

if "%cmd%" == "yarn" (
    docker run -it -p 3000:3000 -p 3001:3001 -v %cd%:/app -v buzzingpixel_node-modules-volume:/app/node_modules -v buzzingpixel_yarn-cache-volume:/usr/local/share/.cache/yarn -w /app --network=buzzingpixel_common-buzzingpixel-network %nodeDockerImage% bash -c "%allArgs%";
    exit /b 0
)

if "%cmd%" == "composer" (
    docker run -it -v %cd%:/app -v buzzingpixel_composer-home-volume:/composer-home-volume --env COMPOSER_HOME=/composer-home-volume -w /app %composerDockerImage% bash -c "%allArgs%"
    exit /b 0
)

if "%cmd%" == "phpcs" (
    docker run -it -v %cd%:/app -w /app buzzingpixel:php-dev bash -c "vendor/bin/phpcs --config-set installed_paths ../../doctrine/coding-standard/lib,../../slevomat/coding-standard; vendor/bin/phpcs src web/index.php bootstrap.php devMode.php config; vendor/bin/php-cs-fixer fix --verbose --dry-run --using-cache=no;"
    exit /b 0
)

if "%cmd%" == "psalm" (
    docker run -it -v %cd%:/app -w /app buzzingpixel:php-dev bash -c "chmod +x /app/vendor/bin/psalm && /app/vendor/bin/psalm"
    exit /b 0
)

if "%cmd%" == "phpstan" (
    docker run -it -v %cd%:/app -w /app buzzingpixel:php-dev bash -c "chmod +x /app/vendor/bin/phpstan && /app/vendor/bin/phpstan analyse config public/index.php src tests cli"
    exit /b 0
)

if "%cmd%" == "phpunit" (
    docker run -it -v %cd%:/app -w /app buzzingpixel:php-dev bash -c "chmod +x /app/vendor/bin/phpunit && /app/vendor/bin/phpunit --configuration /app/phpunit.xml %allArgsExceptFirst%"
    exit /b 0
)

if "%cmd%" == "eslint" (
    docker run -it -v %cd%:/app -v buzzingpixel_node-modules-volume:/app/node_modules -v buzzingpixel_yarn-cache-volume:/usr/local/share/.cache/yarn -w /app %nodeDockerImage% bash -c "node_modules/.bin/eslint assetsSource/js/* assetsSource/tests/*"
    exit /b 0
)

if "%cmd%" == "cypress" (
    docker run -it -v %cd%:/e2e -w /e2e -e CYPRESS_baseUrl=https://buzzingpixel.localtest.me:26087/ --network=host %cypressDockerImage%
    exit /b 0
)

if "%cmd%" == "cypress-interactive" (
    .\platform\node_modules\.bin\cypress open
    exit /b 0
)

echo Specified command not found
exit /b 1
