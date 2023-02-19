![Alt text](docs/logo.png?raw=true "logo")


# Contao Staging Installation Banner

With this extension for **Contao CMS**, you can display a **banner** on your **staging environment** so that there is no confusion with the productive environment during daily work.

**Backend:**
![Alt text](docs/backend.png?raw=true "backend")

**Frontend:**
![Alt text](docs/frontend.png?raw=true "frontend")

In order to display the banner in the frontend, it is necessary to integrate a frontend module into the layout.


And in a further step, you need to make a small change in your configuration on the **staging environment**.

```
# config/config.yaml
# this has to be done on the staging environment only

markocupic_contao_staging_installation_banner:
  is_staging_system: true
```
