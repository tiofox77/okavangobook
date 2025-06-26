<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Verifica se uma imagem existe ou está acessível, e retorna uma imagem padrão caso contrário.
     *
     * @param string|null $imageUrl URL da imagem a ser verificada
     * @param string $type Tipo da imagem (location, hotel, etc)
     * @return string URL da imagem válida ou imagem padrão
     */
    public static function getValidImage(?string $imageUrl, string $type = 'default'): string
    {
        // Se a URL for vazia, retorna SVG padrão
        if (empty($imageUrl)) {
            return self::generateDefaultSvg($type);
        }

        // 1. Verificar se é uma URL completa (http/https)
        if (str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://')) {
            return $imageUrl; // URLs externas são retornadas directamente
        }

        // 2. Verificar se o caminho já contém 'storage/' no início
        if (str_starts_with($imageUrl, 'storage/')) {
            // Verifica se existe no storage público
            if (file_exists(public_path($imageUrl))) {
                return asset($imageUrl);
            }
        }

        // 3. Verificar se é um caminho que começa com 'locations/', 'hotels/', etc.
        $possiblePaths = [
            'storage/' . $imageUrl,  // Tentar prefixar com storage/
            'storage/locations/' . $imageUrl,  // Para imagens de localizações
            'storage/hotels/' . $imageUrl,     // Para imagens de hotéis
            'storage/images/' . $imageUrl,     // Para pasta geral de imagens
            'images/' . $imageUrl,             // Para pasta images no public
            $imageUrl                          // Tentar o caminho original
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // 4. Tentar usar o Laravel Storage para verificar no disco storage
        try {
            // Lista de possíveis caminhos no storage disk
            $storagePaths = [
                $imageUrl,
                'locations/' . $imageUrl,
                'hotels/' . $imageUrl,
                'images/' . $imageUrl,
                'public/' . $imageUrl,
                'public/locations/' . $imageUrl,
                'public/hotels/' . $imageUrl
            ];

            foreach ($storagePaths as $storagePath) {
                if (\Storage::exists($storagePath)) {
                    return \Storage::url($storagePath);
                }
            }
        } catch (\Exception $e) {
            // Se falhar, continua para fallback
        }

        // 5. Se chegou aqui, a imagem não foi encontrada
        // Retorna SVG padrão
        return self::generateDefaultSvg($type);
    }

    /**
     * Retorna uma imagem padrão baseada no tipo.
     *
     * @param string $type Tipo da imagem
     * @return string URL da imagem padrão
     */
    public static function getDefaultImage(string $type = 'default'): string
    {
        $defaultImages = [
            'location' => 'https://images.unsplash.com/photo-1501446529957-6226bd447c46?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'hotel' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'default' => 'https://images.unsplash.com/photo-1518005068251-37900150dfca?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
        ];

        return $defaultImages[$type] ?? $defaultImages['default'];
    }
    
    /**
     * Gera um SVG padrão com base64 para usar como fallback de imagem
     *
     * @param string $type Tipo da imagem (hotel, location, etc)
     * @param string $text Texto a exibir no SVG
     * @param int $width Largura do SVG
     * @param int $height Altura do SVG
     * @return string Data URL do SVG
     */
    public static function generateDefaultSvg(string $type = 'hotel', string $text = '', int $width = 400, int $height = 300): string
    {
        // Cores baseadas no tipo
        $colors = [
            'hotel' => ['bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', 'accent' => '#ffd700', 'text' => '#ffffff'],
            'location' => ['bg' => 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)', 'accent' => '#ff6b6b', 'text' => '#ffffff'],
            'room' => ['bg' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)', 'accent' => '#d63384', 'text' => '#ffffff'],
            'default' => ['bg' => 'linear-gradient(135deg, #d299c2 0%, #fef9d7 100%)', 'accent' => '#6c757d', 'text' => '#ffffff']
        ];
        
        $color = $colors[$type] ?? $colors['default'];
        
        // Texto limitado
        $displayText = $text ? (strlen($text) > 18 ? substr($text, 0, 18) . '...' : $text) : ucfirst($type);
        
        if ($type === 'hotel') {
            $svg = '
            <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">
                <defs>
                    <linearGradient id="hotelGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="buildingGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#f8f9fa;stop-opacity:0.95" />
                        <stop offset="100%" style="stop-color:#e9ecef;stop-opacity:0.95" />
                    </linearGradient>
                </defs>
                
                <!-- Background -->
                <rect width="100%" height="100%" fill="url(#hotelGradient)"/>
                
                <!-- Hotel Building -->
                <g transform="translate(' . ($width/2 - 80) . ', ' . ($height/2 - 60) . ')">
                    <!-- Main Building -->
                    <rect x="20" y="40" width="120" height="80" fill="url(#buildingGradient)" stroke="#dee2e6" stroke-width="2" rx="4"/>
                    
                    <!-- Entrance -->
                    <rect x="70" y="90" width="20" height="30" fill="#6c757d" rx="2"/>
                    
                    <!-- Windows Row 1 -->
                    <rect x="30" y="50" width="12" height="12" fill="#17a2b8" rx="2"/>
                    <rect x="50" y="50" width="12" height="12" fill="#17a2b8" rx="2"/>
                    <rect x="98" y="50" width="12" height="12" fill="#17a2b8" rx="2"/>
                    <rect x="118" y="50" width="12" height="12" fill="#17a2b8" rx="2"/>
                    
                    <!-- Windows Row 2 -->
                    <rect x="30" y="70" width="12" height="12" fill="#17a2b8" rx="2"/>
                    <rect x="50" y="70" width="12" height="12" fill="#ffc107" rx="2"/>
                    <rect x="98" y="70" width="12" height="12" fill="#17a2b8" rx="2"/>
                    <rect x="118" y="70" width="12" height="12" fill="#ffc107" rx="2"/>
                    
                    <!-- Roof -->
                    <polygon points="15,40 80,15 145,40" fill="#dc3545" stroke="#c82333" stroke-width="2"/>
                    
                    <!-- Entrance Awning -->
                    <rect x="65" y="85" width="30" height="8" fill="#28a745" rx="4"/>
                    
                    <!-- Stars -->
                    <g transform="translate(25, 25)">
                        <polygon points="5,0 6,3 10,3 7,5 8,9 5,7 2,9 3,5 0,3 4,3" fill="#ffd700"/>
                        <polygon points="20,0 21,3 25,3 22,5 23,9 20,7 17,9 18,5 15,3 19,3" fill="#ffd700"/>
                        <polygon points="35,0 36,3 40,3 37,5 38,9 35,7 32,9 33,5 30,3 34,3" fill="#ffd700"/>
                        <polygon points="50,0 51,3 55,3 52,5 53,9 50,7 47,9 48,5 45,3 49,3" fill="#ffd700"/>
                        <polygon points="65,0 66,3 70,3 67,5 68,9 65,7 62,9 63,5 60,3 64,3" fill="#ffd700"/>
                    </g>
                    
                    <!-- Hotel Sign -->
                    <rect x="40" y="25" width="80" height="12" fill="#343a40" rx="2"/>
                    <text x="80" y="33" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="8" font-weight="bold">HOTEL</text>
                </g>
                
                <!-- Hotel Name -->
                <text x="50%" y="' . ($height - 25) . '" text-anchor="middle" fill="' . $color['text'] . '" font-family="Arial, sans-serif" font-size="16" font-weight="600">
                    ' . htmlspecialchars($displayText) . '
                </text>
                
                <!-- Decorative Elements -->
                <circle cx="50" cy="50" r="3" fill="' . $color['accent'] . '" opacity="0.6"/>
                <circle cx="' . ($width - 50) . '" cy="50" r="3" fill="' . $color['accent'] . '" opacity="0.6"/>
                <circle cx="50" cy="' . ($height - 50) . '" r="3" fill="' . $color['accent'] . '" opacity="0.6"/>
                <circle cx="' . ($width - 50) . '" cy="' . ($height - 50) . '" r="3" fill="' . $color['accent'] . '" opacity="0.6"/>
            </svg>';
        } elseif ($type === 'location') {
            $svg = '
            <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">
                <defs>
                    <linearGradient id="locationGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#84fab0;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#8fd3f4;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="mapGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#ffa726;stop-opacity:1" />
                    </linearGradient>
                </defs>
                
                <!-- Background -->
                <rect width="100%" height="100%" fill="url(#locationGradient)"/>
                
                <!-- Location Pin -->
                <g transform="translate(' . ($width/2 - 30) . ', ' . ($height/2 - 50) . ')">
                    <!-- Pin Shadow -->
                    <ellipse cx="32" cy="85" rx="12" ry="4" fill="#000000" opacity="0.2"/>
                    
                    <!-- Pin Body -->
                    <path d="M30 20 C15 20, 5 30, 5 45 C5 65, 30 80, 30 80 C30 80, 55 65, 55 45 C55 30, 45 20, 30 20 Z" fill="url(#mapGradient)" stroke="#e53e3e" stroke-width="2"/>
                    
                    <!-- Pin Center -->
                    <circle cx="30" cy="42" r="12" fill="#ffffff" stroke="#e53e3e" stroke-width="2"/>
                    <circle cx="30" cy="42" r="6" fill="#e53e3e"/>
                    
                    <!-- Pulse Rings -->
                    <circle cx="30" cy="42" r="18" fill="none" stroke="#ff6b6b" stroke-width="2" opacity="0.4">
                        <animate attributeName="r" values="18;25;18" dur="2s" repeatCount="indefinite"/>
                        <animate attributeName="opacity" values="0.4;0.1;0.4" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="30" cy="42" r="22" fill="none" stroke="#ff6b6b" stroke-width="1" opacity="0.3">
                        <animate attributeName="r" values="22;30;22" dur="2.5s" repeatCount="indefinite"/>
                        <animate attributeName="opacity" values="0.3;0.05;0.3" dur="2.5s" repeatCount="indefinite"/>
                    </circle>
                </g>
                
                <!-- Location Name -->
                <text x="50%" y="' . ($height - 25) . '" text-anchor="middle" fill="' . $color['text'] . '" font-family="Arial, sans-serif" font-size="16" font-weight="600">
                    ' . htmlspecialchars($displayText) . '
                </text>
                
                <!-- Decorative Stars -->
                <g fill="#ffffff" opacity="0.6">
                    <polygon points="' . ($width * 0.15) . ',' . ($height * 0.15) . ' ' . ($width * 0.15 + 3) . ',' . ($height * 0.15 + 8) . ' ' . ($width * 0.15 + 8) . ',' . ($height * 0.15 + 8) . ' ' . ($width * 0.15 + 5) . ',' . ($height * 0.15 + 12) . ' ' . ($width * 0.15 + 6) . ',' . ($height * 0.15 + 18) . ' ' . ($width * 0.15) . ',' . ($height * 0.15 + 15) . ' ' . ($width * 0.15 - 6) . ',' . ($height * 0.15 + 18) . ' ' . ($width * 0.15 - 5) . ',' . ($height * 0.15 + 12) . ' ' . ($width * 0.15 - 8) . ',' . ($height * 0.15 + 8) . ' ' . ($width * 0.15 - 3) . ',' . ($height * 0.15 + 8) . '"/>
                    <polygon points="' . ($width * 0.85) . ',' . ($height * 0.2) . ' ' . ($width * 0.85 + 2) . ',' . ($height * 0.2 + 6) . ' ' . ($width * 0.85 + 6) . ',' . ($height * 0.2 + 6) . ' ' . ($width * 0.85 + 4) . ',' . ($height * 0.2 + 9) . ' ' . ($width * 0.85 + 5) . ',' . ($height * 0.2 + 13) . ' ' . ($width * 0.85) . ',' . ($height * 0.2 + 11) . ' ' . ($width * 0.85 - 5) . ',' . ($height * 0.2 + 13) . ' ' . ($width * 0.85 - 4) . ',' . ($height * 0.2 + 9) . ' ' . ($width * 0.85 - 6) . ',' . ($height * 0.2 + 6) . ' ' . ($width * 0.85 - 2) . ',' . ($height * 0.2 + 6) . '"/>
                </g>
            </svg>';
        } elseif ($type === 'room') {
            $svg = '
            <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">
                <defs>
                    <linearGradient id="roomGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#a8edea;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#fed6e3;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="bedGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#f8f9fa;stop-opacity:1" />
                    </linearGradient>
                </defs>
                
                <!-- Background -->
                <rect width="100%" height="100%" fill="url(#roomGradient)"/>
                
                <!-- Room Interior -->
                <g transform="translate(' . ($width/2 - 70) . ', ' . ($height/2 - 50) . ')">
                    <!-- Floor -->
                    <rect x="0" y="70" width="140" height="30" fill="#8b7355" opacity="0.6" rx="2"/>
                    
                    <!-- Back Wall -->
                    <rect x="0" y="20" width="140" height="50" fill="#f8f9fa" opacity="0.8" stroke="#dee2e6" stroke-width="1"/>
                    
                    <!-- Bed Base -->
                    <rect x="20" y="55" width="100" height="35" fill="#6c757d" rx="4"/>
                    
                    <!-- Mattress -->
                    <rect x="25" y="50" width="90" height="15" fill="url(#bedGradient)" stroke="#dee2e6" stroke-width="1" rx="6"/>
                    
                    <!-- Pillows -->
                    <ellipse cx="40" cy="45" rx="12" ry="6" fill="#e9ecef" stroke="#dee2e6" stroke-width="1"/>
                    <ellipse cx="65" cy="45" rx="12" ry="6" fill="#e9ecef" stroke="#dee2e6" stroke-width="1"/>
                    <ellipse cx="90" cy="45" rx="12" ry="6" fill="#e9ecef" stroke="#dee2e6" stroke-width="1"/>
                    
                    <!-- Headboard -->
                    <rect x="15" y="30" width="110" height="25" fill="#8b7355" stroke="#6c757d" stroke-width="1" rx="8"/>
                    <rect x="20" y="35" width="20" height="15" fill="#a0a0a0" rx="2"/>
                    <rect x="45" y="35" width="20" height="15" fill="#a0a0a0" rx="2"/>
                    <rect x="70" y="35" width="20" height="15" fill="#a0a0a0" rx="2"/>
                    <rect x="95" y="35" width="20" height="15" fill="#a0a0a0" rx="2"/>
                    
                    <!-- Bedside Tables -->
                    <rect x="5" y="65" width="12" height="20" fill="#8b7355" rx="2"/>
                    <rect x="123" y="65" width="12" height="20" fill="#8b7355" rx="2"/>
                    
                    <!-- Lamps -->
                    <rect x="7" y="60" width="8" height="8" fill="#ffc107" rx="4"/>
                    <rect x="125" y="60" width="8" height="8" fill="#ffc107" rx="4"/>
                    
                    <!-- Window -->
                    <rect x="50" y="25" width="40" height="20" fill="#87ceeb" stroke="#6c757d" stroke-width="1" rx="2"/>
                    <line x1="70" y1="25" x2="70" y2="45" stroke="#6c757d" stroke-width="1"/>
                    <line x1="50" y1="35" x2="90" y2="35" stroke="#6c757d" stroke-width="1"/>
                </g>
                
                <!-- Room Name -->
                <text x="50%" y="' . ($height - 25) . '" text-anchor="middle" fill="' . $color['text'] . '" font-family="Arial, sans-serif" font-size="16" font-weight="600">
                    ' . htmlspecialchars($displayText) . '
                </text>
                
                <!-- Comfort Icons -->
                <g fill="' . $color['accent'] . '" opacity="0.7">
                    <circle cx="' . ($width * 0.15) . '" cy="' . ($height * 0.15) . '" r="8"/>
                    <text x="' . ($width * 0.15) . '" y="' . ($height * 0.15 + 3) . '" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="10">★</text>
                    <circle cx="' . ($width * 0.85) . '" cy="' . ($height * 0.15) . '" r="8"/>
                    <text x="' . ($width * 0.85) . '" y="' . ($height * 0.15 + 3) . '" text-anchor="middle" fill="#ffffff" font-family="Arial" font-size="10">♨</text>
                </g>
            </svg>';
        } else {
            // SVG mais simples para outros tipos
            $icons = [
                'location' => '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>',
                'room' => '<path d="M7 14c1.66 0 3-1.34 3-3S8.66 8 7 8s-3 1.34-3 3 1.34 3 3 3zm0-4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/><path d="M19 7h-8v7H3V6H1v11h2v3h2v-3h8v3h2v-3h4V7h-2zm-2 7h-4V9h4v5z"/>',
                'default' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="M21 15.5c-.31-.34-1.25-1.5-3-1.5s-2.69 1.16-3 1.5"/>'
            ];
            
            $icon = $icons[$type] ?? $icons['default'];
            
            $svg = '
            <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">
                <defs>
                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#84fab0;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#8fd3f4;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <rect width="100%" height="100%" fill="url(#gradient)"/>
                <g transform="translate(' . ($width/2 - 12) . ', ' . ($height/2 - 25) . ')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="' . $color['text'] . '">
                        ' . $icon . '
                    </svg>
                </g>
                <text x="50%" y="' . ($height/2 + 20) . '" text-anchor="middle" fill="' . $color['text'] . '" font-family="Arial, sans-serif" font-size="14" font-weight="500">
                    ' . htmlspecialchars($displayText) . '
                </text>
            </svg>';
        }
        
        return 'data:image/svg+xml;base64,' . base64_encode(trim($svg));
    }
}
