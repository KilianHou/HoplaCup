const fetchCountries = async (selectElement) => {
    const selectedCountry = selectElement.getAttribute('data-selected-country');

    try {
        const urls = [
            'https://data.gouv.nc/api/explore/v2.1/catalog/datasets/liste-des-pays-et-territoires-etrangers/records?select=libcog&order_by=libcog%20ASC&limit=100&offset=0',
            'https://data.gouv.nc/api/explore/v2.1/catalog/datasets/liste-des-pays-et-territoires-etrangers/records?select=libcog&order_by=libcog%20ASC&limit=100&offset=100',
            'https://data.gouv.nc/api/explore/v2.1/catalog/datasets/liste-des-pays-et-territoires-etrangers/records?select=libcog&order_by=libcog%20ASC&limit=100&offset=200'
        ];

        // Effectuer les deux appels d'API
        const responses = await Promise.all(urls.map(url => fetch(url)));
        const data = await Promise.all(responses.map(res => res.json()));

        // Fusionner les résultats
        const results = data.flatMap(response => response.results);

        // Vider les options existantes
        selectElement.innerHTML = '<option value="">-- Sélectionnez un pays --</option>';

        // Ajouter les options reçues
        results.forEach(result => {
            const option = document.createElement('option');
            option.value = result.libcog;
            option.textContent = result.libcog;
            selectElement.appendChild(option);
        });

        // Sélectionner le pays actuel du club
        if(selectedCountry) selectElement.value = selectedCountry;
    } catch (error) {
        console.error('Erreur lors du chargement des pays :', error);
        selectElement.innerHTML = '<option value="">Erreur de chargement</option>';
    }
};