# TAVRO.io API

Full documentation available on Confluence:[https://zoadilack.atlassian.net/wiki/display/TAV/API](https://zoadilack.atlassian.net/wiki/display/TAV/API)

## Access

- Development: [https://api.tavro.dev](https://api.tavro.dev)
- Staging: [https://staging-api.tavro.com](https://staging-api.tavro.com)
- Production: [https://api.tavro.com](https://api.tavro.com)

## Rebuild Api Blueprint

    awk 'FNR==1{print ""}1' api/blueprint/* > blueprint.apib