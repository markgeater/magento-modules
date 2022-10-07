<html>
<body>
<h1>Overview</h1>
<p>Magento 2 is a web-based system, which means that your business staff can log in from anywhere in the world to manage your catalog, check orders and payments, or maintain your store's website content.</p>
<p>This freedom comes with risks - if a backend account is compromised, it can be used by anyone who has the credentials to access all of the information that role is entitled to. This could be your catalog, orders, customer information, or details of payments.</p>
<p>This is where Restrict Role By IP comes in. For each role within your operation, you can define which IP addresses are allowed to log into the Magento backend. This provides complete peace of mind - for instance administrators could be restricted to your head office IPs, order fulfillers to your warehouse or fulfillment center, marketing to your agency's offices.</p>

<h2>Key Features</h2>
<ul>
<li>Define which IP addresses are allowed to log into accounts for each role - via a mixture of explicit IPs and/or CIDR subnet masks. Any roles with no IP addresses configured are open to all.
<li>Configuration validation removes any invalid entries to ensure everything runs as expected.
<li>If a user from an invalid IP tries to log in, they are taken back to the login screen.
<li>In the event of an administrator accidentally locking themselves out, a "Reset" command from the Magento command line removes all restrictions.
</ul>
<h2>Important Notes</h2>
<ul><li>This module only supports IPv4.
<li>Any backend account with access to manage user roles will be able to change the IP restriction settings.
<li>IP restrictions can only be removed through the Magento 2 backend interface, command line, or (as a last resort) through direct database editing. If you only have web access, use this module with extreme caution to avoid locking yourself out.
</ul>
</body>
</html>