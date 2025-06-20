/**
 * Which Police Force Module JavaScript
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

(function() {
    'use strict';
    
    console.log('Which Police Force script loaded');
    
    document.addEventListener('DOMContentLoaded', function() {
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
    });
    
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
            
            if (nameEl) {
                nameEl.textContent = data.force_name || data.force || 'Unknown Force';
            }
            
            if (neighbourhoodEl) {
                let neighbourhoodText = 'Neighbourhood: ' + (data.neighbourhood || 'Unknown');
                if (data.postcode) {
                    neighbourhoodText = 'Postcode ' + data.postcode + ' - ' + neighbourhoodText;
                }
                neighbourhoodEl.textContent = neighbourhoodText;
            }
            
            // Display area information if available
            if (areaEl && data.area) {
                areaEl.innerHTML = '';
                const areaItems = [];
                
                if (data.area.district) {
                    areaItems.push('District: ' + data.area.district);
                }
                if (data.area.ward) {
                    areaItems.push('Ward: ' + data.area.ward);
                }
                if (data.area.parish) {
                    areaItems.push('Parish: ' + data.area.parish);
                }
                if (data.area.constituency) {
                    areaItems.push('Constituency: ' + data.area.constituency);
                }
                if (data.area.region) {
                    areaItems.push('Region: ' + data.area.region);
                }
                if (data.area.country) {
                    areaItems.push('Country: ' + data.area.country);
                }
                
                if (areaItems.length > 0) {
                    const areaP = document.createElement('p');
                    areaP.className = 'mb-2';
                    areaP.innerHTML = areaItems.join(' • ');
                    areaEl.appendChild(areaP);
                }
            }
            
            if (linksEl) {
                linksEl.innerHTML = '';
                
                // Add website link if available
                if (data.force_url) {
                    const link = document.createElement('a');
                    link.href = data.force_url;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    link.textContent = 'Visit Police Force Website';
                    link.className = 'btn btn-sm btn-outline-primary me-2';
                    linksEl.appendChild(link);
                }
                
                // Add telephone if available
                if (data.force_telephone) {
                    const telEl = document.createElement('span');
                    telEl.className = 'text-muted';
                    telEl.innerHTML = '<i class="fas fa-phone"></i> ' + data.force_telephone;
                    linksEl.appendChild(telEl);
                }
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