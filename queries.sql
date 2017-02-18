SELECT
  webHookLog.id,
  webHookLog.topic,
  webHookLog.verified,
  webHookLog.success,
  webHookLog.entityCreated,
  webHookLog.entityId,
  webHookLog.externalId,
  webHookLog.notes,
  webHookLog.errorMessage,
  webHookLog.createdAt
FROM
  ShopifyWebHookLog webHookLog
  JOIN IntegratedShoppingCart integratedShoppingCart ON integratedShoppingCart.id = webHookLog.integratedShoppingCartId
  JOIN IntegratedService integratedService ON integratedService.id = integratedShoppingCart.id
  JOIN Client client ON client.id = integratedShoppingCart.clientId
  JOIN Organization organization ON organization.id = client.organizationId;


select id, integratedShoppingCartId, topic, verified, success, entityId, externalId, entityCreated, notes, errorMessage, createdAt from ShopifyWebHookLog;
select id, integratedShoppingCartId, topic, verified, success, entityId, externalId, entityCreated, notes, errorMessage, createdAt from ShopifyWebHookLog where errorMessage is not null;

select count(*), queue from jobs group by queue;

select id, queue, attempts, reserved_at, available_at, created_at from jobs;
select id, queue, attempts, reserved_at, available_at, created_at from jobs where queue = 'shopifyOrders';

SELECT count(*), orderStatus.id, orderStatus.name FROM Orders orders JOIN OrderStatus orderStatus ON orderStatus.id = orders.statusId GROUP BY orders.statusId;

select count(*), orderItemId FROM ShipmentItem GROUP BY orderItemId HAVING count(*) > 1;

SELECT @@global.sql_mode;
ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION


SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';


SELECT
  count(*) AS total,
  vi.*,
  i.*
FROM
  VariantInventory vi
  JOIN Inventory i ON i.id = vi.id
WHERE
  i.inventoryLocationId = 1976
GROUP BY
  vi.variantId
ORDER BY
  count(*) DESC;


SELECT
  *
FROM
  VariantInventory vi
  JOIN Inventory i ON i.id = vi.id
WHERE
  i.inventoryLocationId = 1976;


SELECT
  si.shipmentId,
  si.id,
  si.quantity,
  si.quantityReserved,
  oi.variantId
FROM
  ShipmentItem si
  JOIN OrderItem oi ON si.orderItemId = oi.id
WHERE
  oi.variantId = 7;