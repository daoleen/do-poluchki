FROM quay.io/keycloak/keycloak:26.5.0

ENV KC_DB=postgres

# Jar-ы провайдера и его обязательные зависимости
ADD --chown=1000:0 https://repo1.maven.org/maven2/ru/playa/keycloak/keycloak-russian-providers/26.5.0.1/keycloak-russian-providers-26.5.0.1.jar /opt/keycloak/providers/
ADD --chown=1000:0 https://repo1.maven.org/maven2/com/jayway/jsonpath/json-path/2.10.0/json-path-2.10.0.jar /opt/keycloak/providers/
ADD --chown=1000:0 https://repo1.maven.org/maven2/net/minidev/json-smart/2.6.0/json-smart-2.6.0.jar /opt/keycloak/providers/
ADD --chown=1000:0 https://repo1.maven.org/maven2/net/minidev/accessors-smart/2.6.0/accessors-smart-2.6.0.jar /opt/keycloak/providers/
ADD --chown=1000:0 https://repo1.maven.org/maven2/org/ow2/asm/asm/9.7.1/asm-9.7.1.jar /opt/keycloak/providers/


# Собираем оптимизированную конфигурацию прямо при сборке образа
RUN /opt/keycloak/bin/kc.sh build

# start-dev — режим разработки со встроенной H2-базой, для прода - start + настройки базы нужно подсовывать
CMD ["start"]
