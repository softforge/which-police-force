/**
 * Which Police Force Module JavaScript
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

(function() {
    'use strict';
    
    // Mark that the script is loaded
    window.whichPoliceForceScriptLoaded = true;
    
    console.log('Which Police Force script loaded');
    
    // Also check if DOM is already loaded (in case script loads after DOMContentLoaded)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeModules);
    } else {
        // DOM is already loaded
        initializeModules();
    }
    
    function initializeModules() {
        console.log('DOM loaded, checking for module config...');
        console.log('Config available:', typeof window.whichPoliceForceConfig !== 'undefined');
        
        // Initialize all module instances
        if (window.whichPoliceForceConfig) {
            console.log('Found config for modules:', Object.keys(window.whichPoliceForceConfig));
            Object.keys(window.whichPoliceForceConfig).forEach(function(moduleId) {
                initializeModule(moduleId);
            });
        } else {
            console.error('No whichPoliceForceConfig found!');
        }
    }
    
    function initializeModule(moduleId) {
        console.log('Initializing Which Police Force module:', moduleId);
        const config = window.whichPoliceForceConfig[moduleId];
        const form = document.getElementById('whichpoliceforce-form-' + moduleId);
        const resultDiv = document.getElementById('whichpoliceforce-result-' + moduleId);
        const errorDiv = document.getElementById('whichpoliceforce-error-' + moduleId);
        const loadingDiv = document.getElementById('whichpoliceforce-loading-' + moduleId);
        
        console.log('Config:', config);
        console.log('Form found:', !!form);
        
        if (!form) {
            console.error('Form not found for module:', moduleId);
            return;
        }
        
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            e.preventDefault();
            console.log('Config:', config);
            
            const postcodeInput = form.querySelector('.whichpoliceforce-postcode');
            const postcode = postcodeInput.value.trim();
            console.log('Postcode entered:', postcode);
            
            if (!postcode) {
                showError('Please enter a postcode');
                return;
            }
            
            // Validate postcode format (basic UK postcode validation)
            const postcodeRegex = /^[A-Z]{1,2}[0-9][0-9A-Z]?\s?[0-9][A-Z]{2}$/i;
            if (!postcodeRegex.test(postcode)) {
                showError('Please enter a valid UK postcode');
                return;
            }
            
            lookupPoliceForce(postcode);
        });
        
        function lookupPoliceForce(postcode) {
            showLoading();
            hideError();
            hideResult();
            
            // Make AJAX request with CSRF token
            const token = Joomla.getOptions('csrf.token', '');
            fetch(config.ajaxUrl + '&postcode=' + encodeURIComponent(postcode) + '&' + token + '=1')
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    
                    if (data.success && data.data) {
                        showResult(data.data);
                    } else {
                        showError(data.message || 'Unable to find police force for this postcode');
                    }
                })
                .catch(error => {
                    hideLoading();
                    showError('An error occurred. Please try again.');
                    console.error('Error:', error);
                });
        }
        
        function showResult(data) {
            const nameEl = resultDiv.querySelector('.police-force-name');
            const neighbourhoodEl = resultDiv.querySelector('.police-force-neighbourhood');
            const areaEl = resultDiv.querySelector('.police-force-area');
            const linksEl = resultDiv.querySelector('.police-force-links');
            
            // Line 1: Police Force Name with inline website and phone
            if (nameEl) {
                nameEl.innerHTML = '';
                
                // Force name
                const nameSpan = document.createElement('span');
                nameSpan.textContent = data.force_name || data.force || 'Unknown Force';
                nameEl.appendChild(nameSpan);
                
                // Add website link inline if available and enabled
                if (config.showWebsite && data.force_url) {
                    const link = document.createElement('a');
                    link.href = data.force_url;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    link.textContent = 'Website';
                    link.className = 'btn btn-sm btn-outline-primary ms-3';
                    nameEl.appendChild(link);
                }
                
                // Add telephone inline if available and enabled
                if (config.showPhone && data.force_telephone) {
                    const telLink = document.createElement('a');
                    telLink.href = 'tel:' + data.force_telephone.replace(/\s/g, '');
                    telLink.className = 'ms-3 text-decoration-none';
                    telLink.innerHTML = '<i class="fas fa-phone"></i> ' + data.force_telephone;
                    telLink.title = 'Click to call';
                    nameEl.appendChild(telLink);
                }
            }
            
            // Line 2: Postcode and Neighbourhood
            if (neighbourhoodEl) {
                neighbourhoodEl.innerHTML = '';
                const parts = [];
                
                if (config.showPostcode && data.postcode) {
                    parts.push('Postcode: ' + data.postcode);
                }
                
                if (config.showNeighbourhood && data.neighbourhood) {
                    parts.push('Neighbourhood: ' + data.neighbourhood);
                }
                
                if (config.showCoordinates && data.latitude && data.longitude) {
                    parts.push('Coordinates: ' + data.latitude.toFixed(6) + ', ' + data.longitude.toFixed(6));
                }
                
                if (parts.length > 0) {
                    neighbourhoodEl.textContent = parts.join(' • ');
                    neighbourhoodEl.style.display = 'block';
                } else {
                    neighbourhoodEl.style.display = 'none';
                }
            }
            
            // Line 3: Area details
            if (areaEl) {
                areaEl.innerHTML = '';
                
                if (data.area) {
                    const areaItems = [];
                    
                    if (config.showDistrict && data.area.district) {
                        areaItems.push('District: ' + data.area.district);
                    }
                    if (config.showWard && data.area.ward) {
                        areaItems.push('Ward: ' + data.area.ward);
                    }
                    if (config.showParish && data.area.parish) {
                        areaItems.push('Parish: ' + data.area.parish);
                    }
                    if (config.showConstituency && data.area.constituency) {
                        areaItems.push('Constituency: ' + data.area.constituency);
                    }
                    if (config.showRegion && data.area.region) {
                        areaItems.push('Region: ' + data.area.region);
                    }
                    if (config.showCountry && data.area.country) {
                        areaItems.push('Country: ' + data.area.country);
                    }
                    
                    if (areaItems.length > 0) {
                        const areaP = document.createElement('p');
                        areaP.className = 'mb-0';
                        areaP.innerHTML = areaItems.join(' • ');
                        areaEl.appendChild(areaP);
                        areaEl.style.display = 'block';
                    } else {
                        areaEl.style.display = 'none';
                    }
                } else {
                    areaEl.style.display = 'none';
                }
            }
            
            // Hide the old links container since we moved everything inline
            if (linksEl) {
                linksEl.style.display = 'none';
            }
            
            resultDiv.style.display = 'block';
        }
        
        function showError(message) {
            const errorMessage = errorDiv.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.textContent = message;
            }
            errorDiv.style.display = 'block';
        }
        
        function hideError() {
            errorDiv.style.display = 'none';
        }
        
        function hideResult() {
            resultDiv.style.display = 'none';
        }
        
        function showLoading() {
            loadingDiv.style.display = 'inline-block';
        }
        
        function hideLoading() {
            loadingDiv.style.display = 'none';
        }
    }
})();