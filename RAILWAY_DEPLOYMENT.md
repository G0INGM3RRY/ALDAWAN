# ALDAWAN - Railway Deployment Guide

## ✅ Pre-Deployment Checklist

Your project is ready for Railway deployment!

## 📋 Step-by-Step Deployment

### 1. Create Railway Account
- Go to: https://railway.app
- Click "Login with GitHub"
- Authorize Railway to access your repositories

### 2. Create New Project
- Click "New Project"
- Select "Deploy from GitHub repo"
- Choose: **G0INGM3RRY/ALDAWAN**
- Railway will auto-detect it's a Laravel project

### 3. Add MySQL Database
- In your project dashboard, click "New"
- Select "Database" → "Add MySQL"
- Railway will create a database and provide credentials

### 4. Configure Environment Variables

Click on your Laravel service → "Variables" tab → Add these:

**Required Variables:**
```
APP_NAME=ALDAWAN
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME=ALDAWAN
```

**Generate APP_KEY:**
```bash
# Run this locally to generate a key
php artisan key:generate --show

# Copy the output (starts with base64:)
# Paste it as APP_KEY in Railway
```

### 5. Deploy!
- Railway will automatically deploy
- Click "Deploy" if it doesn't start
- Wait 3-5 minutes for build to complete

### 6. Get Your URL
- Once deployed, Railway provides a URL like:
  `https://aldawan-production.up.railway.app`
- Click "Generate Domain" if not auto-generated

### 7. Run Migrations
- Go to your service → "Settings" → "Deploy"
- Migrations run automatically via Procfile
- Or use Railway CLI:
  ```bash
  railway run php artisan migrate --force
  ```

### 8. Test Your Application
Visit your Railway URL and test:
- ✅ Registration works
- ✅ Email verification sends
- ✅ Login works
- ✅ All features functional

---

## 🔧 Troubleshooting

### If deployment fails:
1. Check "Deployments" tab for error logs
2. Verify all environment variables are set
3. Make sure APP_KEY is generated

### If database connection fails:
- Check MySQL service is running
- Verify database variables use Railway's references: `${{MySQL.VARIABLE_NAME}}`

### If email doesn't work:
- Use Gmail App Password (not regular password)
- Enable "Less secure app access" or use App Passwords

---

## 💰 Pricing

**Railway Pricing (as of 2025):**
- $5/month minimum
- Includes $5 usage credit
- MySQL database included
- Pay only for what you use

**For Capstone:**
- Perfect for demo/defense
- Can cancel after presentation
- Or keep for portfolio

---

## 📝 What to Document in Your Capstone

**Simply state:**
> "The ALDAWAN system is deployed on Railway, a cloud platform service that hosts the Laravel application and MySQL database. The application is accessible via [your-url].railway.app"

**Technical Stack (no changes needed):**
- Framework: Laravel 11
- Database: MySQL 8.0
- Hosting: Railway Cloud Platform
- Email: Gmail SMTP

---

## 🚀 Next Steps After Deployment

1. **Update your documentation** with the live URL
2. **Test all features** thoroughly online
3. **Show your panelists** the live site during defense
4. **Add to resume/portfolio** after graduation

---

## 📞 Need Help?

If you encounter issues:
1. Check Railway's build logs
2. Review environment variables
3. Ask me for help!

**Files created for deployment:**
- ✅ Procfile (tells Railway how to run Laravel)
- ✅ Your existing .env.example (Railway uses for reference)

**Ready to deploy!** 🎉
