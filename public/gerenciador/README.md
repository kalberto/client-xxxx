nvm CMS Theme
=============

Instalação
-----------

USING NODE 6.17.0

```
npm install
bower install
```

Uso
-----------

Durante o desenvolvimento, para recompilar o css
```
gulp watch
```

###Staging

```
gulp watch --env=staging
```

###Produção

```
gulp watch --env=production
```

Para gerar uma nova versão do dist, minificando e comprimindo os arquivos
```
gulp build
```

