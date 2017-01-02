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

select count(*), queue from jobs group by queue;

select id, queue, attempts, reserved_at, available_at, created_at from jobs;
select id, queue, attempts, reserved_at, available_at, created_at from jobs where queue = 'shopifyOrders';

SELECT count(*), orderStatus.id, orderStatus.name FROM Orders orders JOIN OrderStatus orderStatus ON orderStatus.id = orders.statusId GROUP BY orders.statusId;

select count(*), orderItemId FROM ShipmentItem GROUP BY orderItemId HAVING count(*) > 1;