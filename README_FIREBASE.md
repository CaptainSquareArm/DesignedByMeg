Firebase Hosting: Deploy DesignedByMeg to Firebase

GitHub Repository
- https://github.com/CaptainSquareArm/DesignedByMeg.git

Files created for Firebase Hosting:
- `firebase.json` — hosting config (public: `public/`).
- `.firebaserc.example` — template. Copy to `.firebaserc` and set your Firebase project ID.
- `.firebaserc` — (in .gitignore, local-only) your project config.
- `public/index.html` — placeholder. Replace with your static site files.
- `.github/workflows/firebase-hosting-deploy.yml` — automated deploy workflow.

Notes before you run anything:
- Firebase Hosting serves static files (HTML/CSS/JS). Your repository appears to contain a WordPress/PHP site; Firebase Hosting will NOT execute PHP. Export or generate static HTML first and place it in `public/`.
- `.firebaserc` is in `.gitignore` so it's not committed to git. Each developer/environment creates their own.

Quick start (after cloning the repo):

```powershell
# 1) Clone the repo (if you haven't already)
git clone https://github.com/CaptainSquareArm/DesignedByMeg.git
cd DesignedByMeg

# 2) Copy the template and configure your Firebase project:
Copy-Item .firebaserc.example .firebaserc
# Edit .firebaserc and replace 'your-firebase-project-id-here' with your Firebase project ID

# 3) Install Firebase CLI (if needed)
npm install -g firebase-tools

# 4) Login to Firebase
firebase login

# 5) Deploy immediately (or set up CI below)
firebase deploy --only hosting

# 6) Or test locally first
npm start
```

Environment & project setup:
- `.firebaserc.example` is the template. Don't edit it. Copy it to `.firebaserc` and update with your project ID.
- There are no API keys or secrets in the code. If you add server-side features (Cloud Functions, AI proxy), use GitHub Secrets to store tokens.

GitHub-to-Firebase automated deploy:

Instead of running `firebase deploy` locally, you can push to GitHub and let GitHub Actions deploy automatically:

1) Get a Firebase CI token (run locally):

```powershell
firebase login:ci
```
This opens your browser and prints a token. Copy it.

2) Add GitHub Secrets (in your repo: Settings > Secrets and variables > Actions):
- `FIREBASE_TOKEN` — the token from step 1.
- `FIREBASE_PROJECT_ID` — your Firebase Project ID (from Firebase Console > Project settings).

3) Push to `main`:
- When you push, `.github/workflows/firebase-hosting-deploy.yml` runs automatically.
- It calls `firebase deploy --only hosting` and publishes to Firebase Hosting.
- You can watch progress in GitHub > Actions.

Security: Your `FIREBASE_TOKEN` is secret and only used by GitHub Actions CI. Don't add it to code.
