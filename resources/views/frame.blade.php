<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <!-- Farcaster Frame V1 Meta Tags -->
    <meta property="fc:frame" content="vNext" />
    <meta property="fc:frame:image" content="{{ $image }}" />
    <meta property="fc:frame:button:1" content="Refresh Status" />
    <meta property="fc:frame:button:2" content="Play Now" />
    <meta property="fc:frame:button:2:action" content="link" />
    <meta property="fc:frame:button:2:target" content="{{ url('/') }}" />
    <meta property="fc:frame:post_url" content="{{ $postUrl }}" />

    <!-- Open Graph for standard previews -->
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:image" content="{{ $image }}" />
</head>
<body style="background: #0f172a; color: white; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif;">
    <div style="text-align: center;">
        <h1>{{ $title }}</h1>
        <p>{{ $status }}</p>
        <p><a href="{{ url('/') }}" style="color: #8b5cf6;">Go to NumberPuzzle</a></p>
    </div>
</body>
</html>
