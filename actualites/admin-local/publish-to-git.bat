@echo off
REM Manually publish articles to Git
REM Usage: publish-to-git.bat "commit message"

cd /d "%~dp0..\.."

REM Default message if none provided
set MESSAGE=%~1
if "%MESSAGE%"=="" set MESSAGE=Update blog articles

echo =========================================
echo   Publishing Blog to Git
echo =========================================
echo.
echo Commit message: %MESSAGE%
echo.

REM Add files
echo Adding files...
git add actualites/posts/
git add actualites/images/

REM Commit
echo Committing...
git commit -m "%MESSAGE%"

REM Push
echo Pushing to remote...
git push

if %ERRORLEVEL% EQU 0 (
    echo.
    echo =========================================
    echo   Successfully published to Git!
    echo =========================================
) else (
    echo.
    echo =========================================
    echo   Push failed. Check your connection.
    echo =========================================
)

pause
