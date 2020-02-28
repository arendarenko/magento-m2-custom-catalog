# CustomCatalog sample module for Magento 2

This is sample Magento 2 module. It creates "alternative" catalog with products (they called "custom products" here) based on custom EAV entity type.
It also supports multistore attributes.

Each custom product contains such attributes:
- ID
- SKU (string, unique, global)
- VPN (Vendor Product Number, string, unique, global)
- Copy Write Info (text, store scope)

Module provides CRUD functionality for custom products and provides several web API endpoints

## Supported Magento 2 versions
Magento 2.3.3+ (probably it supports **2.3.x** but not tested yet).

## Module configuration and usage
Module is enabled by default.
 
To disable module go to: **Stores → Configuration → Catalog → Custom Catalog**

In order to manage custom products go to **Catalog → Custom Catalog** (when module is enabled).

## Web API 

Module provides such web API endpoints: 

#### [GET] `/V1/product/getByVPN/:vpn` (where `:vpn` - VPN value)
Returns information about custom product by it's VPN (vendor product number).
Requires such ACL permissions: `Arendarenko_CustomCatalog::webapi_view` 

#### [PUT] `/V1/product/update/`
Async product update (by it's entity id as primary field) using Message Queue mechanism (works with RabbitMQ broker).  
Requires such ACL permissions: `Arendarenko_CustomCatalog::webapi_update`

Example request body (make sure you have product with ID = 1!):

`{
	"customProduct":
	{
		"id": 1,
		"sku": "PRD-12345",
		"vpn": "999111",
		"copy_write_info": "Actually I would prefer to be named 'copyright info', but..."
	}
}`

## Few notes about Message Queue
In order to start module consumer manually please run: `magento queue:consumers:start CustomCatalogProductUpdate`
