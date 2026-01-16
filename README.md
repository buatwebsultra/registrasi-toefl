# TOEFL Registration System

A Laravel application for TOEFL exam registration at Universitas Halu Oleo Kendari.

## Deployment to Hostinger

### Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer
- Git (optional)

### Installation Steps for Production

1. **Upload Files**
   - Upload all files to your hosting directory (excluding the `vendor` folder)
   - Make sure to upload the `public/.htaccess` file

2. **Install Dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Environment Configuration**
   - Create a `.env` file based on `.env.example`
   - Generate an app key: `php artisan key:generate`
   - Configure your database settings in `.env`

4. **Directory Permissions**
   - Set write permissions for `storage` and `bootstrap/cache`:
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

6. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

7. **Set Document Root**
   - Point your domain's document root to the `public` directory of this application

### Configuration Files

- `.env.example` - Example environment configuration
- `DEPLOYMENT_INSTRUCTIONS.md` - Detailed deployment steps for Hostinger
- `public/.htaccess` - Apache rewrite rules

### Troubleshooting

If you encounter issues:

1. **Check Error Logs**: Check your hosting control panel for error logs
2. **Verify Permissions**: Ensure storage and bootstrap/cache are writable
3. **Database Connection**: Verify your database credentials are correct
4. **PHP Version**: Ensure your hosting supports PHP 8.0+
5. **htaccess**: Make sure the .htaccess file is properly uploaded to the public directory

### Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Hostinger Documentation](https://support.hostinger.com/)

---

For development setup, please refer to the local installation instructions.