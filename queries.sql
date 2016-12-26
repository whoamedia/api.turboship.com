SELECT
  webHookLog.id,
  organization.name AS 'Organization',
  client.name AS 'Client',
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