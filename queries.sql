SELECT
  webHookLog.id AS 'WebHookLog id',
  organization.name AS 'Organization',
  client.name AS 'Client',
  integration.name AS 'Integration',
  integrationWebHook.topic AS 'Topic',
  webHookLog.verified,
  webHookLog.success,
  webHookLog.errorMessage
FROM
  WebHookLog webHookLog
  JOIN ClientIntegration clientIntegration ON clientIntegration.id = webHookLog.clientIntegrationId
  JOIN Client client ON client.id = clientIntegration.clientId
  JOIN Organization organization ON organization.id = client.organizationId
  JOIN Integration integration ON integration.id = clientIntegration.integrationId
  JOIN IntegrationWebHook integrationWebHook ON integrationWebHook.id = webHookLog.integrationWebHookId;