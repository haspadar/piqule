# ----------------------------------------
# Base image
# ----------------------------------------
FROM php:8.4-cli

# ----------------------------------------
# OCI labels
# ----------------------------------------
LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Quality control for PHP projects"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

# ----------------------------------------
# Placeholders for future sections
# ----------------------------------------
# System dependencies
# Composer & PHP tooling
# Linters (actionlint, markdownlint, etc.)
# Entrypoint

WORKDIR /app

CMD ["bash"]