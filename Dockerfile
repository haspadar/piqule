# syntax=docker/dockerfile:1

ARG PHP_IMAGE=php:8.3-cli-bookworm
ARG NODE_IMAGE=node:24.12.0-bookworm

# ============================================================
# Linters
# ============================================================
ARG ACTIONLINT_VERSION=1.7.10
ARG HADOLINT_VERSION=2.14.0
ARG MARKDOWNLINT_VERSION=0.20.0
ARG YAMLLINT_VERSION=1.37.1
ARG TYPOS_VERSION=1.41.0

# ============================================================
# AST Metrics
# ============================================================
ARG AST_METRICS_VERSION=0.31.0

# ============================================================
# Node.js stage (source of node + npm)
# ============================================================
FROM ${NODE_IMAGE} AS node

# ============================================================
# PHP base stage
# ============================================================
FROM ${PHP_IMAGE}

# ----------------------------------------
# OCI labels
# ----------------------------------------
LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Piqule — PHP Quality Laws"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ------------------------------------------------------------
# Re-declare ARGs
# ------------------------------------------------------------
ARG ACTIONLINT_VERSION
ARG HADOLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG YAMLLINT_VERSION
ARG TYPOS_VERSION
ARG AST_METRICS_VERSION

# ------------------------------------------------------------
# Node.js (copy runtime from node image)
# ------------------------------------------------------------
COPY --from=node /usr/local /usr/local
ENV PATH="/usr/local/bin:/usr/local/lib/node_modules/.bin:${PATH}"

# ------------------------------------------------------------
# Unified build step
# ------------------------------------------------------------
RUN set -eux; \
    \
    # -------------------------------------------------------- \
    # System packages \
    # -------------------------------------------------------- \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        ca-certificates \
        bash \
        git \
        curl \
        unzip \
        fish \
        python3 \
        python3-venv \
        pipx \
        libicu-dev \
        libzip-dev \
        zlib1g-dev \
        libonig-dev; \
    rm -rf /var/lib/apt/lists/*; \
    \
    # -------------------------------------------------------- \
    # PHP extensions \
    # -------------------------------------------------------- \
    docker-php-ext-install intl zip mbstring; \
    \
    # -------------------------------------------------------- \
    # Architecture detection \
    # -------------------------------------------------------- \
    ARCH="$(uname -m)"; \
    case "$ARCH" in \
      x86_64) \
        ACTIONLINT_ARCH="amd64"; \
        HADOLINT_ARCH="x86_64"; \
        TYPOS_ARCH="x86_64"; \
        AST_ARCH="x86_64" ;; \
      aarch64) \
        ACTIONLINT_ARCH="arm64"; \
        HADOLINT_ARCH="arm64"; \
        TYPOS_ARCH="aarch64"; \
        AST_ARCH="arm64" ;; \
      *) echo "Unsupported architecture: $ARCH" >&2; exit 1 ;; \
    esac; \
    \
    # -------------------------------------------------------- \
    # Composer (verified installer) \
    # -------------------------------------------------------- \
    curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer; \
    export COMPOSER_ALLOW_SUPERUSER=1; \
    export COMPOSER_HOME=/usr/local/composer; \
    \
    # -------------------------------------------------------- \
    # actionlint \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_${ACTIONLINT_ARCH}.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/actionlint; \
    \
    # -------------------------------------------------------- \
    # markdownlint-cli2 \
    # -------------------------------------------------------- \
    npm install -g "markdownlint-cli2@${MARKDOWNLINT_VERSION}"; \
    npm cache clean --force; \
    \
    # -------------------------------------------------------- \
    # yamllint (pipx) \
    # -------------------------------------------------------- \
    pipx install "yamllint==${YAMLLINT_VERSION}"; \
    \
    # -------------------------------------------------------- \
    # hadolint \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-linux-${HADOLINT_ARCH}" \
      -o /usr/local/bin/hadolint; \
    chmod +x /usr/local/bin/hadolint; \
    \
    # -------------------------------------------------------- \
    # typos \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-${TYPOS_ARCH}-unknown-linux-musl.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/typos; \
    \
    # -------------------------------------------------------- \
    # AST Metrics \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/Halleck45/ast-metrics/releases/download/v${AST_METRICS_VERSION}/ast-metrics_Linux_${AST_ARCH}" \
      -o /usr/local/bin/ast-metrics; \
    chmod +x /usr/local/bin/ast-metrics; \
    \
    # -------------------------------------------------------- \
    # Runtime user \
    # -------------------------------------------------------- \
    useradd --uid 1000 --create-home --shell /bin/bash appuser

# ============================================================
# Runtime
# ============================================================
USER appuser
WORKDIR /app
CMD ["bash"]
