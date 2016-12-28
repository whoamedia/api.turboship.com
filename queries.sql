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
  JOIN Organization organization ON organization.id = client.organizationId
  WHERE webHookLog.entityId IS NULL and webHookLog.externalId IS NULL AND webHookLog.notes IS NULL and webHookLog.errorMessage IS NULL;


select id, integratedShoppingCartId, topic, verified, success, entityId, externalId, entityCreated, notes, errorMessage, createdAt from ShopifyWebHookLog;

select count(*), queue from jobs group by queue;