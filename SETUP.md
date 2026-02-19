# Setup Guide for DesignedByMeg Firebase Hosting

## For the maintainer (first time only)

1. **Create a Firebase project** (if you don't have one):
   - Go to [Firebase Console](https://console.firebase.google.com/)
   - Create a new project called "DesignedByMeg" (or similar)
   - Enable Hosting
   - Copy your Project ID

2. **Create `.firebaserc` locally** (will NOT be committed):
   ```powershell
   Copy-Item .firebaserc.example .firebaserc
   # Edit .firebaserc and set your project ID
   ```

3. **Generate a CI token for GitHub Actions**:
   ```powershell
   npm install -g firebase-tools
   firebase login:ci
   ```
   This outputs a long token. Copy it.

4. **Add GitHub Secrets** (in your repo on github.com):
   - Go to Settings > Secrets and variables > Actions
   - Create `FIREBASE_TOKEN` = the token from step 3
   - Create `FIREBASE_PROJECT_ID` = your Firebase Project ID

5. **Push your code**:
   ```powershell
   git add .
   git commit -m "Add Firebase Hosting config"
   git push origin main
   ```
   GitHub Actions will automatically deploy to Firebase Hosting on every push to `main`.

## For developers cloning the repo

1. **Clone the repo**:
   ```powershell
   git clone https://github.com/CaptainSquareArm/DesignedByMeg.git
   cd DesignedByMeg
   ```

2. **Create your local `.firebaserc`**:
   ```powershell
   Copy-Item .firebaserc.example .firebaserc
   # Edit with your Firebase project ID (or ask the maintainer)
   ```

3. **Install dependencies** (optional, for local testing):
   ```powershell
   npm install -g firebase-tools
   ```

4. **Test locally** (optional):
   ```powershell
   npm start
   # Opens http://localhost:5000 (Firebase Hosting emulator)
   ```

5. **Push your changes**:
   ```powershell
   git add public/  # Add updated static files
   git commit -m "Update site content"
   git push origin main
   ```
   GitHub Actions will deploy automatically.

## File structure

```
DesignedByMeg/
├── .github/
│   └── workflows/
│       └── firebase-hosting-deploy.yml   (CI workflow)
├── public/                                (static site goes here)
│   └── index.html
├── .gitignore                             (excludes .firebaserc, node_modules, etc)
├── .firebaserc.example                    (template for local config)
├── firebase.json                          (Firebase Hosting config)
├── package.json                           (npm scripts)
├── README_FIREBASE.md                     (Firebase setup details)
└── SETUP.md                               (this file)
```

## Troubleshooting

- **"firebase not found"**: Run `npm install -g firebase-tools`
- **".firebaserc" not working**: Make sure you edited `.firebaserc` and replaced the placeholder project ID
- **Deploy failed in GitHub Actions**: Check that `FIREBASE_TOKEN` and `FIREBASE_PROJECT_ID` secrets are set correctly in Settings > Secrets
- **Local deploy fails**: Run `firebase logout` then `firebase login` to re-authenticate
