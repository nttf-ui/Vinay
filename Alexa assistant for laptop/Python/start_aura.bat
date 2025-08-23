@echo off

REM This command changes the directory to where the batch file is located.
REM This ensures the script runs correctly no matter where you launch it from.
cd /d "%~dp0"

REM Use pyw.exe to run the script without a console window.
REM "start" helps launch it as a separate, non-blocking process.
start "Aura Background Process" pyw.exe aura_assistant.py