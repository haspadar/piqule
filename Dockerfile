# syntax=docker/dockerfile:1

ARG PHP_IMAGE=php:8.2-cli-bookworm
ARG NODE_IMAGE=node:24.12.0-bookworm

# ============================================================
# Linters (aligned with CI)
# ============================================================
ARG ACTIONLINT_VERSION=1.7.10
ARG HADOLINT_VERSION=2.14.0
ARG MARKDOWNLINT_VERSION=0.20.0
ARG YAMLLINT_VERSION=1.37.1
ARG TYPOS_VERSION=1.40.1

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
# AST Metrics
# ============================================================
ARG AST_METRICS_VERSION=0.31.0

# ============================================================
# Node.js stage (official image, source of node/npm)
# ============================================================
FROM ${NODE_IMAGE} AS node

# ============================================================
# PHP base stage
# ============================================================
FROM ${PHP_IMAGE}

# Explicitly document root usage (CI / tooling image)
USER root

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ------------------------------------------------------------
# Re-declare ARGs for this stage
# ------------------------------------------------------------
ARG ACTIONLINT_VERSION
ARG HADOLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG YAMLLINT_VERSION
ARG TYPOS_VERSION

ARG PHP_CS_FIXER_VERSION
ARG PHPUNIT_VERSION
ARG INFECTION_VERSION
ARG PHPSTAN_VERSION
ARG PSALM_VERSION
ARG PHPMD_VERSION

ARG AST_METRICS_VERSION

# ============================================================
# System packages + Composer (single layer, pinned)
# ============================================================
RUN set -eux; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      ca-certificates=20230311+deb12u1 \
      bash=5.2.15-2+b9 \
      git=1:2.39.5-0+deb12u2 \
      curl=7.88.1-10+deb12u14 \
      unzip=6.0-28 \
      fish=3.6.0-3.1+deb12u1 \
      python3=3.11.2-1+b1 \
      python3-pip=23.0.1+dfsg-1 \
      libicu-dev=72.1-3+deb12u1 \
      libzip-dev=1.7.3-1+b1 \
      zlib1g-dev=1:1.2.13.dfsg-1 \
      libonig-dev=6.9.8-1; \
    \
    rm -rf /var/lib/apt/lists/*; \
    \
    curl -sS https://getcomposer.org/installer | php -- \
      --install-dir=/usr/local/bin \
      --filename=composer

# ============================================================
# PHP extensions
# ============================================================
RUN docker-php-ext-install intl zip mbstring

# ============================================================
# Node.js (copied from official image)
# ============================================================
COPY --from=node /usr/local /usr/local
ENV PATH="/usr/local/lib/node_modules/.bin:${PATH}"

# ============================================================
# Composer environment
# ============================================================
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/usr/local/composer
ENV PATH="/usr/local/composer/vendor/bin:${PATH}"

# ============================================================
# Linters (architecture-aware)
# ============================================================
RUN set -eux; \
    ARCH="$(uname -m)"; \
    case "$ARCH" in \
      x86_64)  ACTIONLINT_ARCH="amd64"; HADOLINT_ARCH="x86_64"; TYPOS_ARCH="x86_64" ;; \
      aarch64) ACTIONLINT_ARCH="arm64"; HADOLINT_ARCH="arm64"; TYPOS_ARCH="aarch64" ;; \
      *) echo "Unsupported architecture: $ARCH" >&2; exit 1 ;; \
    esac; \
    \
    # actionlint \
    curl -sSfL \
      "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_${ACTIONLINT_ARCH}.tar.gz" \
      -o /tmp/actionlint.tar.gz; \
    tar -xzf /tmp/actionlint.tar.gz -C /usr/local/bin; \
    chmod +x /usr/local/bin/actionlint; \
    rm /tmp/actionlint.tar.gz; \
    \
    # markdownlint-cli2 \
    npm install -g "markdownlint-cli2@${MARKDOWNLINT_VERSION}"; \
    \
    # yamllint \
    pip3 install --no-cache-dir --break-system-packages \
      "yamllint==${YAMLLINT_VERSION}"; \
    \
    # hadolint \
    curl -sSfL \
      "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-linux-${HADOLINT_ARCH}" \
      -o /usr/local/bin/hadolint; \
    chmod +x /usr/local/bin/hadolint; \
    \
    # typos \
    curl -sSfL \
      "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-${TYPOS_ARCH}-unknown-linux-musl.tar.gz" \
      -o /tmp/typos.tar.gz; \
    tar -xzf /tmp/typos.tar.gz -C /usr/local/bin; \
    chmod +x /usr/local/bin/typos; \
    rm /tmp/typos.tar.gz

# ============================================================
# AST Metrics
# ============================================================
RUN set -eux; \
    ARCH="$(uname -m)"; \
    case "$ARCH" in \
      x86_64)  BIN="ast-metrics_Linux_x86_64" ;; \
      aarch64) BIN="ast-metrics_Linux_arm64" ;; \
      *) echo "Unsupported architecture: $ARCH" >&2; exit 1 ;; \
    esac; \
    curl -sSfL \
      "https://github.com/Halleck45/ast-metrics/releases/download/v${AST_METRICS_VERSION}/${BIN}" \
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
# Runtime user (non-root)
# ============================================================
RUN useradd --uid 1000 --create-home --shell /bin/bash appuser

USER appuser

# ============================================================
# Runtime
# ============================================================
WORKDIR /app
CMD ["bash"]
