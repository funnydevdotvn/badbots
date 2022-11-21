<?php

# composer require google/cloud
# composer require google/apiclient

use Google\ApiCore\ValidationException;
use Google\Cloud\Compute\V1\FirewallsClient;
use Throwable;

class FirewallSystem extends Controller
{
    private FirewallsClient $firewallClient;

    private array $credentials;

    /**
     * @throws ValidationException
     */
    public function __construct()
    {
        # Create a service account from https://console.cloud.google.com/iam-admin/serviceaccounts?project=your_project_id_here
        # you will get a secret json file then you need put its content into this array
        $this->credentials = [
            'type' => 'fill_your_data_here',
            'project_id' => 'fill_your_data_here',
            'private_key_id' => 'fill_your_data_here',
            'private_key' => "fill_your_data_here",
            'client_email' => 'fill_your_data_here',
            'client_id' => 'fill_your_data_here',
            'auth_uri' => 'fill_your_data_here',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => 'fill_your_data_here'
        ];
        $options = ['credentials' => $this->credentials, 'projectId' => $this->credentials['project_id'], 'keyFile' => 'fill_your_secret_json_path_here'];
        $this->firewallClient = new FirewallsClient($options);
    }

    # This function is to get all your blocked IPs on Google Cloud Network Firewall
    public function get(): array
    {
        try {
            $response = $this->firewallClient->get('blocked-ips', $this->credentials['project_id']);

            return (array) $response->getSourceRanges();
        } catch (Throwable) {
        }

        return [];
    }

    # This function is to block an IPs on Google Cloud Network Firewall
    public function block(string $ips): bool
    {
        try {
            $get = $this->get();
            if ($this->valid($get)) {
                if (! in_array($ips, array_shift($get))) {
                    $response = $this->firewallClient->get('blocked-ips', $this->credentials['project_id']);
                    $blocked = $response->getSourceRanges();
                    $blocked->offsetSet(null, $ips);
                    $response->setSourceRanges($blocked);
                    $this->firewallClient->update('blocked-ips', $response, $this->credentials['project_id']);
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Throwable) {
        }

        return false;
    }

    # This function is to unblock an IPs on Google Cloud Network Firewall
    public function unblock(string $ips): bool
    {
        try {
            $get = $this->get();
            if ($this->valid($get)) {
                $index = array_search($ips, array_shift($get));
                if ($this->valid($index)) {
                    $response = $this->firewallClient->get('blocked-ips', $this->credentials['project_id']);
                    $blocked = $response->getSourceRanges();
                    $blocked->offsetUnset($index);
                    $response->setSourceRanges($blocked);
                    $this->firewallClient->update('blocked-ips', $response, $this->credentials['project_id']);

                    return true;
                }

                return false;
            }
        } catch (Throwable) {
        }

        return false;
    }
}
