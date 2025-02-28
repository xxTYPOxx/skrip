#!/bin/bash
outputFile="/dir/targetfile.php"
url="#url#"
while true; do
    serverFileTime=$(curl -sI "$url" | grep -i Last-Modified | cut -d' ' -f2-)
    serverFileTime=$(date -d "$serverFileTime" +%s)
    if [ -f "$outputFile" ]; then
        localFileTime=$(stat -c %Y "$outputFile")
    else
        localFileTime=0 # Jika file tidak ada, anggap timestamp lokal 0
    fi
    if [ "$serverFileTime" -gt "$localFileTime" ]; then
        wget -q "$url" -O "$outputFile"
        if [ $? -eq 0 ]; then
            echo "File berhasil diunduh: $outputFile"
        else
            echo "Terjadi kesalahan saat mengunduh file."
        fi
    else
        echo "Tidak ada perubahan pada file."
    fi
    sleep 1 # 1 detik
done
