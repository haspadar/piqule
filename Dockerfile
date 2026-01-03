# syntax=docker/dockerfile:1

ARG PHP_IMAGE=php:8.3-cli-bookworm
ARG NODE_IMAGE=node:24.12.0-bookworm

# ============================================================
# Linters (tracked by Renovate)
# ============================================================
ARG ACTIONLINT_VERSION=1.7.10
ARG HADOLINT_VERSION=2.14.0
ARG MARKDOWNLINT_VERSION=0.20.0
ARG YAMLLINT_VERSION=1.37.1
ARG TYPOS_VERSION=1.41.0
ARG AST_METRICS_VERSION=0.31.0

# ============================================================
# Composer tools
# ============================================================
ARG PHP_CS_FIXER_VERSION=3.92.3
ARG PHPUNIT_VERSION=11.5.46
ARG INFECTION_VERSION=0.32.0
ARG PHPSTAN_VERSION=2.1.33
ARG PSALM_VERSION=6.14.3
ARG PHPMD_VERSION=2.15.0

# ============================================================
# Node.js stage
# ============================================================
FROM ${NODE_IMAGE} AS node

# ============================================================
# PHP base stage
# ============================================================
FROM ${PHP_IMAGE}

# Explicitly run as root (tooling / CI image)
USER root

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ------------------------------------------------------------
# Re-declare ARGs for this stage (required for set -u)
# ------------------------------------------------------------
ARG ACTIONLINT_VERSION
ARG HADOLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG YAMLLINT_VERSION
ARG TYPOS_VERSION
ARG AST_METRICS_VERSION

ARG PHP_CS_FIXER_VERSION
ARG PHPUNIT_VERSION
ARG INFECTION_VERSION
ARG PHPSTAN_VERSION
ARG PSALM_VERSION
ARG PHPMD_VERSION

# ============================================================
# System packages
# ============================================================
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        ca-certificates \
        bash \
        git \
        curl \
        unzip \
        fish \
        python3 \
        python3-pip \
        python3-venv \
        pipx \
        libicu-dev \
        libzip-dev \
        zlib1g-dev \
        libonig-dev; \
    rm -rf /var/lib/apt/lists/*

# ============================================================
# PHP extensions
# ============================================================
RUN docker-php-ext-install intl zip mbstring

# ============================================================
# Node.js (from official image)
# ============================================================
COPY --from=node /usr/local /usr/local
ENV PATH="/usr/local/lib/node_modules/.bin:${PATH}"

# ============================================================
# Composer (official installer with verification)
# ============================================================
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/usr/local/composer
ENV PATH="/usr/local/composer/vendor/bin:${PATH}"

RUN set -eux; \
    curl -sS https://getcomposer.org/installer | php -- \
      --install-dir=/usr/local/bin \
      --filename=composer

# ============================================================
# Linters (architecture-aware)
# ============================================================
RUN set -eux; \
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
    # actionlint
    curl -sSfL \
      "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_${ACTIONLINT_ARCH}.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/actionlint; \
    \
    # markdownlint-cli2
    npm install -g "markdownlint-cli2@${MARKDOWNLINT_VERSION}"; \
    npm cache clean --force; \
    \
    # yamllint (via pipx)
    pipx install "yamllint==${YAMLLINT_VERSION}"; \
    \
    # hadolint
    curl -sSfL \
      "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-linux-${HADOLINT_ARCH}" \
      -o /usr/local/bin/hadolint; \
    chmod +x /usr/local/bin/hadolint; \
    \
    # typos
    curl -sSfL \
      "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-${TYPOS_ARCH}-unknown-linux-musl.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/typos; \
    \
    # AST Metrics
    curl -sSfL \
      "https://github.com/Halleck45/ast-metrics/releases/download/v${AST_METRICS_VERSION}/ast-metrics_Linux_${AST_ARCH}" \
      -o /usr/local/bin/ast-metrics; \
    chmod +x /usr/local/bin/ast-metrics

# ============================================================
# Composer tools (global)
# ============================================================
RUN set -eux; \
    composer global config --no-plugins allow-plugins.infection/extension-installer true; \
    composer global require --no-interaction --no-progress --prefer-dist \
      friendsofphp/php-cs-fixer:${PHP_CS_FIXER_VERSION} \
      phpunit/phpunit:${PHPUNIT_VERSION} \
      phpstan/phpstan:${PHPSTAN_VERSION} \
      vimeo/psalm:${PSALM_VERSION} \
      phpmd/phpmd:${PHPMD_VERSION} \
      infection/infection:${INFECTION_VERSION}; \
    composer clear-cache

# ============================================================
# Runtime user
# ============================================================
RUN useradd --uid 1000 --create-home --shell /bin/bash appuser
USER appuser

# ============================================================
# Runtime
# ============================================================
WORKDIR /app
CMD ["bash"]
