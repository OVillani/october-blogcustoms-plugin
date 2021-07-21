;(function(factory) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', factory);
    } else {
        factory();
    }
}(function() {
    "use strict";

    /**
     * Constructor
     * @param {HTMLElement} repeater 
     */
    function CustomRepeater(repeater) {
        let template = document.querySelector('[data-customrepeater-template]');
        if (!template) {
            throw new Error('The CustomRepeater template is missing!');
        }

        this.repeater = repeater;
        this.fieldList = repeater.querySelector('ul');
        this.template = template.content.children[0];
        this.init();
    }

    /**
     * Prototypes
     */
    CustomRepeater.prototype = {
        /**
         *  Core - Repeater Element
         */
        repeater: null,
        
        /**
         *  Core - Field List
         */
        fieldList: null,
         
        /**
         *  Core - Field Set Template
         */
        template: null,

        /**
         *  Field Sets
         */
        fieldSets: [],

        /**
         * New Field Counter
         */
        newFieldCounter: 0,
        
        /**
         * Initialize Repeater Field
         * @returns {void}
         */
        init: function() {
            if (this.fieldList.querySelectorAll('li').length > 0) {
                this.fieldSets = [].map.call(this.fieldList.querySelectorAll('li'), (fieldset) => {
                    this.attachFieldSet(fieldset);
                    return fieldset;
                });
            }

            // Listen to Add Button
            let addAction = this.repeater.querySelector('[data-customrepeater-action="add"]');
            if (addAction) {
                addAction.addEventListener('click', (event) => this.addFieldSet(event));
            }

            // Listen to Remove Button
            this.fieldList.addEventListener('click', (event) => {
                let found;
                let target = event.target;

                while (target && target.matches && target !== this.fieldList && !(found = target.matches('[data-customrepeater-action="remove"]'))) {
                    target = target.parentElement;
                }
                if (found) {
                    this.removeFieldSet(event, target);
                }
            });
        },

        /**
         * Attach Field Set Validation & Sanitization
         * @param {void} fieldset 
         */
        attachFieldSet: function(fieldset) {
            [].map.call(fieldset.querySelectorAll('input, select'), (field) => {
                if (field.tagName.toUpperCase() === 'SELECT') {
                    jQuery(field).on('change', (event) => {
                        this.validateField(fieldset);
                    });
                } else {
                    field.addEventListener('input', (event) => {
                        if (field.parentElement.dataset.field === 'key') {
                            this.validateKeyField(field, event);
                        } else {
                            this.validateField(fieldset);
                        }
                    });
                }
            });
        },

        /**
         * Field Validation 
         * @returns {boolean}
         */
        validateField: function(fieldset) {
            let type = fieldset.querySelector('[data-field="type"] select').value;
            let field = fieldset.querySelector('[data-field="value"] input');
            let value = field.value;

            let valid = false;
            if (type === 'number' && value.length > 0) {
                valid = /^[0-9.,]+$/.test(value);
            } else {
                valid = true;
            }

            if (valid) {
                field.parentElement.classList.remove('has-error');
                return true;
            } else {
                field.parentElement.classList.add('has-error');
                return false;
            }
        },

        /**
         * Validate Key Field
         * @returns 
         */
        validateKeyField: function(field, event) {
            let value = field.value;

            if (/^[a-z0-9_]+$/.test(value) && /^[^0-9]/.test(value)) {
                field.parentElement.classList.remove('has-error');
            } else {
                field.parentElement.classList.add('has-error');
            }
        },

        /**
         * Add a new Fieldset
         * @returns {void}
         */
        addFieldSet: function(event) {
            event.preventDefault();

            // New Fieldset
            let fieldset = this.template.cloneNode(true);
            fieldset.classList.add('item-new');
            [].map.call(fieldset.querySelectorAll('input,select'), (field) => {
                field.name = field.name.replace('_id', `_${this.newFieldCounter}`);
                return field;
            });

            // Append
            this.fieldSets.push(fieldset);
            this.fieldList.appendChild(fieldset);
            this.attachFieldSet(fieldset);

            // Select2
            let selectize = fieldset.querySelector('select');
            if (selectize) {
                jQuery(selectize).select2({ minimumResultsForSearch: Infinity });
            }

            // Counter
            this.newFieldCounter++;
        },

        /**
         * Remove existing Fieldset
         * @returns {void}
         */
        removeFieldSet: function(event, target) {
            event.preventDefault();

            // Get Field Set
            let fieldset = target;
            while (fieldset && fieldset !== this.fieldList && fieldset.tagName.toUpperCase() !== 'LI') {
                fieldset = fieldset.parentElement;
            }

            // Remove Item
            let index = this.fieldSets.indexOf(fieldset);
            if (index >= 0) {
                this.fieldSets.splice(index, 1);
            }
            this.fieldList.removeChild(fieldset);
        }
    };

    // Initialize
    let repeaters = document.querySelectorAll('[data-control="synder-customrepeater"]');
    [].map.call(repeaters, (repeater) => {
        new CustomRepeater(repeater);
    });
}));