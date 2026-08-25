@echo off
cd /d "C:\wampNEW64\www\sisadmed.local.002\Config"

"C:\wampNEW64\bin\php\php8.3.28\php.exe" "C:\wampNEW64\www\sisadmed.local.002\Config\obtener_bcv.php" >> "cron_resultado.log" 2>&1