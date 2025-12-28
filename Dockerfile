# System dependencies
# hadolint ignore=DL3008
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip curl bash fish python3 python3-pip \
        libicu-dev libzip-dev zlib1g-dev libonig-dev \
    && docker-php-ext-install intl zip mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

ARG NODE_VERSION

# Node.js
RUN curl -fsSL "https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.xz" -o node.tar.xz \
    && tar -xf node.tar.xz -C /usr/local --strip-components=1 \
    && rm node.tar.xz

ARG PHP_CS_FIXER_VERSION

# PHP-CS-Fixer
RUN composer global require friendsofphp/php-cs-fixer:${PHP_CS_FIXER_VERSION} \
    && ln -s /usr/local/composer/vendor/bin/php-cs-fixer /usr/local/bin/php-cs-fixer \
    && composer clear-cache \
    && mkdir -p /usr/local/piqule

COPY configs/php-cs-fixer.php /usr/local/piqule/php-cs-fixer.base.php

# markdownlint-cli2 + yamllint
RUN npm install -g markdownlint-cli2@${MARKDOWNLINT_VERSION} \
    && pip3 install --no-cache-dir --break-system-packages yamllint==${YAMLLINT_VERSION}

ARG HADOLINT_VERSION

# hadolint
RUN curl -sSfL \
        "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-Linux-x86_64" \
        -o /usr/local/bin/hadolint \
    && chmod +x /usr/local/bin/hadolint \
