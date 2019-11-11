<link rel="canonical" href="$AbsoluteURL"/>
<meta property="og:title" content="$Title.XML"/>
<meta property="og:description" content="$Description.XML"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="$AbsoluteURL"/>
<meta property="og:locale" content="$Locale" />
<meta property="og:site_name" content="$SiteName.XML" />

<% if $Image %>
    <meta property="og:image" content="$Image.Fill(1200,628).AbsoluteURL" />
    <% if $SSL %>
        <meta property="og:image:secure_url" content="$Image.Fill(1200,628).AbsoluteURL" />
    <% end_if %>
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="628" />
<% end_if %>

<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="$Title.XML"/>
<meta name="twitter:description" content="$Description.XML"/>
<meta name="twitter:creator" content="$TwitterUser.XML"/>
<meta name="twitter:site" content="$TwitterUser.XML" />

<% if $Image %>
    <meta name="twitter:image" content="$Image.Fill(1200,628).AbsoluteURL" />
<% end_if %>

<meta property="article:published_time" content="$Created" />
<meta property="article:modified_time" content="$LastEdited" />
