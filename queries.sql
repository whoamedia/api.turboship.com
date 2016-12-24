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
  JOIN ClientECommerceIntegration clientECommerceIntegration ON clientECommerceIntegration.id = webHookLog.clientECommerceIntegrationId
  JOIN ClientIntegration clientIntegration ON clientIntegration.id = clientECommerceIntegration.id
  JOIN Client client ON client.id = clientECommerceIntegration.clientId
  JOIN Organization organization ON organization.id = client.organizationId;


select id, clientECommerceIntegrationId, topic, verified, success, entityId, externalId, entityCreated, notes, errorMessage, createdAt from ShopifyWebHookLog;