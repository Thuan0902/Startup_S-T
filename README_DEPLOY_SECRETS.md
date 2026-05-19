# Secrets and deployment steps

To enable automatic deploys from GitHub Actions to Render or Fly.io, add the following repository secrets in GitHub (Settings → Secrets & variables → Actions):

- `RENDER_API_KEY` — Render API key (if you want Actions to trigger a Render deploy).
- `RENDER_SERVICE_ID` — ID of the Render service (available in Render dashboard after creating the service).
- `FLY_API_TOKEN` — Fly.io API token (if you want to use Fly deploy workflows).

After adding secrets, push a commit to `main` to trigger `.github/workflows/deploy.yml`.

Notes:
- Keep production `.env` values out of the repo. Use Render or Fly environment settings.
- If you need, I can add a Fly deploy step to Actions once you confirm using Fly.io.
