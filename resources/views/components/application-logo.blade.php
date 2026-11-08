<svg viewBox="0 80 800 360" xmlns="http://w3.org" {{ $attributes }}>
    <defs>
        <style>
            /* 🎨 Paleta de Identidad - El Gurí SRL */
            .guri-chasis-base { fill: #F3F4F6; }       /* Gris claro estructural */
            .guri-chasis-azul { fill: #2563EB; }       /* Azul Eléctrico (60% Dominante) */
            .guri-oro-acento { fill: #FBBF24; }        /* Amarillo Oro Metálico (10%) */
            
            /* 🛞 Grupo Ruedas Cohesivas */
            .guri-neumatico { fill: #1E293B; }         /* Negro pizarra */
            .guri-llanta { fill: #E2E8F0; }             /* Aluminio brillante */
            
            /* 🪟 Grupo Ventanas y Ópticas */
            .guri-vidrio { fill: #DBEAFE; fill-opacity: 0.85; } /* Cristal traslúcido */
            .guri-marcos { stroke: #1E293B; stroke-width: 8; stroke-linecap: round; stroke-linejoin: round; fill: none; }
        </style>
    </defs>

    <g id="guri-vector-bus" transform="translate(40, 20)">
        
        <!-- 🚌 GRUPO 1: ESTRUCTURA Y CHASIS GENERAL -->
        <g id="bus-body">
            <!-- Techo y Cuerpo Superior -->
            <rect x="50" y="80" width="620" height="240" rx="40" class="guri-chasis-base" />
            <!-- Climatizador de Techo -->
            <path d="M 520 80 L 540 50 L 610 50 L 630 80 Z" class="guri-chasis-base" />
            <!-- Chasis Inferior (Franja Corporativa Azul) -->
            <path d="M 50 240 L 670 240 L 670 300 C 670 310, 660 320, 650 320 L 70 320 C 60 320, 50 310, 50 300 Z" class="guri-chasis-azul" />
            <!-- Línea de Estilo de Media Altura -->
            <rect x="50" y="215" width="620" height="12" class="guri-chasis-azul" />
        </g>

        <!-- 🪟 GRUPO 2: MARCOS Y CRISTALES DE VENTANAS -->
        <g id="bus-windows">
            <!-- Paño de Vidrio Continuo de Fondo -->
            <rect x="80" y="110" width="560" height="85" rx="10" class="guri-vidrio" />
            <!-- Marcos Estructurales Individuales -->
            <rect x="95" y="120" width="115" height="65" rx="8" class="guri-marcos" />
            <rect x="230" y="120" width="115" height="65" rx="8" class="guri-marcos" />
            <rect x="365" y="120" width="115" height="65" rx="8" class="guri-marcos" />
            <rect x="500" y="120" width="115" height="65" rx="8" class="guri-marcos" />
        </g>

        <!-- 🛞 GRUPO 3: RUEDAS COMPUESTAS INDEXADAS -->
        <g id="bus-wheels">
            <!-- Rueda Delantera -->
            <g id="wheel-front">
                <circle cx="190" cy="320" r="62" class="guri-neumatico" />
                <circle cx="190" cy="320" r="62" class="guri-marcos" />
                <circle cx="190" cy="320" r="34" class="guri-llanta" />
                <circle cx="190" cy="320" r="16" class="guri-oro-acento" />
            </g>
            <!-- Rueda Trasera -->
            <g id="wheel-rear">
                <circle cx="530" cy="320" r="62" class="guri-neumatico" />
                <circle cx="530" cy="320" r="62" class="guri-marcos" />
                <circle cx="530" cy="320" r="34" class="guri-llanta" />
                <circle cx="530" cy="320" r="16" class="guri-oro-acento" />
            </g>
        </g>

        <!-- 💡 GRUPO 4: SEÑALIZACIÓN, ÓPTICAS Y DETALLES COMERCIALES -->
        <g id="bus-details">
            <!-- Flecha de Sentido de Circulación Converso -->
            <path d="M 605 260 L 625 270 L 605 280 Z" class="guri-oro-acento" />
            <rect x="575" y="266" width="30" height="8" class="guri-oro-acento" />
            <!-- Ópticas Delanteras Activas -->
            <rect x="34" y="250" width="16" height="40" rx="5" class="guri-oro-acento" />
            <!-- Ópticas Posteriores de Frenado -->
            <rect x="670" y="260" width="16" height="30" rx="3" class="guri-oro-acento" />
            <!-- Parachoques Absorbentes Mecánicos -->
            <rect x="26" y="290" width="24" height="35" rx="6" class="guri-chasis-base" />
            <rect x="26" y="290" width="24" height="35" rx="6" class="guri-marcos" />
            <rect x="670" y="290" width="24" height="35" rx="6" class="guri-chasis-base" />
            <rect x="670" y="290" width="24" height="35" rx="6" class="guri-marcos" />
        </g>

        <!-- Línea Externa de Refuerzo de Chasis -->
        <path d="M 50 200 L 50 120 C 50 95, 70 80, 95 80 L 625 80 C 650 80, 670 95, 670 120 L 670 320" fill="none" stroke="#1E293B" stroke-width="8" stroke-linecap="round" />
    </g>
</svg>
