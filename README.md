# Project API Web Scraping

API Rest Desenvolvido em PHP inicialmente utilizando o framework Laravel alterado para o Lumen. O Lumen é um framework 
baseado em Laravel no qual seu objetivo é dar suporte no desenvolvimento de API's, por essa simplicidade e sendo um projeto
objeto mais "limpo" foi escolhido para o sistema de extrações de dados Web Scaraping.
Abaixo listo algumas informações técnicas da composição do projeto sendo elas mais notáveis.

- PHP versão 7.3
- Versão do framework Laravel 7.29
- Composer >= 1.6
- symfony/dom-crawler na versão 5.2 para manipulação DOM (Document Object Model)
- Apache versão 2 ou Ngnix
- É necessário a utilização da extensão XML do PHP na sua respectiva versão
- MySQL versão 5.7

## Procedimentos

- O composer vai baixar os pacotes necessários para execução do projeto
- Apache ou Nginx deve ter habilitado a rescrita de URI
- Necessário especificar informações no arquivo enviroment .env
  -- No arquivo .env example deve ser renomeado para .env apenas e especificado configurações
  de conexão com o banco de dados
- O versionamento do banco de dados está no migration do Laravel, todas especificações das tabelas. Necessário a execução 
  do comando `php artisan migrate` para a instação das tabelas.
  -- Seguindo nessa ordem necessário da execução da inserção de dados da tabela **selector_collection_default** onde 
  é armazenadas coleção de seletores de valores como título, descrição, preço e imagem especificado na coluna 
  [collection_selector_json] do tipo **json**  para uso automatizados da extração de dados no seus respectivos sites.
  -- Para a concretização do citado acima deve-se ser executado o comando para população de dados de sistes já cadastrados 
  como **Amazon**, **Magazine Luiza** e **zattini**. Claro podem ser inseridas diversos sites mencionando apenas os seletores 
  de valores para extração.
  `php artisan db:seed` 

## Arquitetura do projeto

- Utilizando a arquitetura do framework Lumen/Laravel MVC, técnicas da utilização da ORM Eloquent, sistemas de rotas
- Implementando o componente **WebScrapingExtraction** localizado em `app/Components/WebScrapingExtraction` 
pacote para requisição, interação e extração das páginas enviadas via GET para o serviço.
A justificativa de criar o compoente é para melhor refatorização das tarefas atribuídas.
  viu a necessidade de criar um pacote responsável para extração dos dados das páginas enviadas pelo usuário.

## Ciclo de vida da requisição GET para o recurso /api/v1/extraction/scraping

1. Request para a URI /api/v1/extraction/scraping?urlOrigin=*https://www.example.com.br/site/product-test*
2. Matching na Rota **/api/v1/extraction/scraping**
3. Rota redirecionando para Controller responsável neste caso Extaction/WebScrapingController
4. O método atribuido para a tarefa chama-se **doScraping** método público.
5. O método **doScraping** da controller **WebScrapingController** instancia e executa o componente WebScraping método **run**
6. A classe instânciada **WebScraping** obriga a especificação da URL do produto do qual deve extrair. 


## Utilizando a aplicação

1. Utilize alguma ferramenta para requisições API REST como Postman e Insomnia.
2. Faça uma requisição do tipo GET para a URL/URI `http://localhost/webscraping_api/public/api/v1/extraction/scraping?urlOrigin=http://www.example.com.br/product-test` 
   com site já pré cadastrado na base **selector_collection_default**.
   
## Tabela de sites cadastrado na tabela Selector Collection Default

```
www.amazon.com.br  - Amazon Brazil

{
  "image": "#imgTagWrapperId > img", 
  "price": "#price_inside_buybox", 
  "title": "#productTitle", 
  "description": "#productDescription"
}

www.magazineluiza.com.br - Magazine Luiza

{
  "image": ".showcase-product__big-img.js-showcase-big-img", 
  "price": ".price-template__text", 
  "title": ".header-product__title", 
  "description": ".description__container-text"
}


www.zattini.com.br - Zattini

{
  "image": ".floating-button__wrap > .floating-button__wrap--col > img", 
  "price": ".default-price", 
  "title": ".short-description > h1", 
  "description": ".description"
}

```

## Resposta do recurso /api/v1/extraction/scraping

```
{
  "title" : "string"
  "description": "string",
  "price": 0.00,
  "image": "img base64",
  "url" : "string"
}

** Observação a imagem é codificada em base64 onde foi armazenado na tabela **products**.
```