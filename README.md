# Badbots
 Bad bots data collect from our reality working

* ModSecurity_UserAgent: Mod Security's rule helps to block connections with empty useragent or containing offending terms in the text file (one phrase per line).
* attackers.txt: List of IPs that attack the server (including real people and bots) through methods: Brute force, URL scan, SQLi & Xss attack...
* spam.txt: List of spam IPs collected on Google and GitHub from providers like Spamhaus...
* asn.txt: This ASN (Autonomous System Number) list is used to set up blocking connections from IPs belonging to providers in this list. For example in Mod Security rules or Cloudflare. The definition of which IP belongs to which ASN is based on the Maxmind database.
* cloudflare_waf: Including setting up website firewall on Cloudflare (WAF), in which priority is to block User-Agent 1 and 2. The remaining settings are for reference, especially blocking other countries such as RU, UK, CN , DE... based on a large and frequent attack volume is the author's personal opinion only, not suitable for users with audiences in these countries. You can also create rules to block IPs from attackers.txt file.

We will regularly update the necessary data for this git.