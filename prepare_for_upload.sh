#!/bin/bash

# Script to prepare TOEFL Registration System for Hostinger deployment

echo "----------------------------------------------------------"
echo "Menyiapkan Sistem Registrasi TOEFL untuk Hostinger..."
echo "----------------------------------------------------------"

# 1. Pastikan folder zip ada
if ! command -v zip &> /dev/null; then
    echo "Error: 'zip' tidak terinstall. Silakan install zip terlebih dahulu."
    exit 1
fi

# 2. Opsional: Jalankan build Vite jika perlu (Laravel 10+)
if [ -f "package.json" ]; then
    echo "Mendeteksi package.json. Menjalankan 'npm install && npm run build'..."
    npm install && npm run build
fi

# 3. Buat folder sementara untuk bundling
TEMP_DIR="dist_upload_$(date +%s)"
mkdir -p "$TEMP_DIR"

echo "Menyalin file aplikasi ke folder sementara..."

# 4. Salin file dengan rsync, mengecualikan folder development
rsync -av \
  --exclude="vendor/" \
  --exclude=".env" \
  --exclude=".env.local" \
  --exclude=".git/" \
  --exclude=".github/" \
  --exclude=".gitignore" \
  --exclude=".vscode/" \
  --exclude="node_modules/" \
  --exclude="storage/logs/*" \
  --exclude="storage/framework/cache/data/*" \
  --exclude="storage/framework/sessions/*" \
  --exclude="storage/framework/views/*" \
  --exclude=".editorconfig" \
  --exclude="*.log" \
  --exclude="phpunit.xml" \
  --exclude="package-lock.json" \
  --exclude="composer.lock" \
  --exclude="*.sqlite" \
  --exclude="backups/" \
  --exclude="dist_upload_*/" \
  --exclude="temp_upload_*/" \
  --exclude="prepare_for_upload.sh" \
  . "$TEMP_DIR/"

echo "Membuat arsip ZIP..."

# 5. Archive
zip_name="toefl_registration_system.zip"
rm -f "$zip_name"
cd "$TEMP_DIR" || exit
zip -r "../$zip_name" . -x "*.DS_Store" "*/.DS_Store"
cd ..

# 6. Cleanup
rm -rf "$TEMP_DIR"

echo "----------------------------------------------------------"
echo "BERHASIL! File siap upload: $zip_name"
echo "----------------------------------------------------------"
echo "Langkah selanjutnya:"
echo "1. Upload '$zip_name' ke Hostinger File Manager."
echo "2. Ekstrak di direktori tujuan."
echo "3. Baca DEPLOYMENT_INSTRUCTIONS.md untuk konfigurasi .env dan database."
echo "----------------------------------------------------------"