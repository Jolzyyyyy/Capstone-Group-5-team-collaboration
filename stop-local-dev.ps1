$ErrorActionPreference = 'SilentlyContinue'

function Stop-ListeningProcesses {
    param(
        [int[]]$Ports,
        [string]$Label
    )

    $connections = Get-NetTCPConnection -State Listen | Where-Object { $_.LocalPort -in $Ports }
    $pids = $connections | Select-Object -ExpandProperty OwningProcess -Unique

    if (-not $pids) {
        Write-Host "$Label is not running."
        return
    }

    foreach ($processId in $pids) {
        $process = Get-Process -Id $processId -ErrorAction SilentlyContinue
        if ($process) {
            Stop-Process -Id $processId -Force
            Write-Host "$Label stopped (PID $processId)."
        }
    }
}

Stop-ListeningProcesses -Ports @(8000) -Label 'Laravel'
Stop-ListeningProcesses -Ports @(8025, 1025) -Label 'Mailpit'
