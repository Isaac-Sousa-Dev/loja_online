import { initProductCreateWizard } from './product-create-wizard';

document.addEventListener('DOMContentLoaded', () => {
    if (!window.productEditWizardConfig) {
        return;
    }
    const c = window.productEditWizardConfig;
    initProductCreateWizard({
        submitUrl: c.updateUrl,
        productsIndexUrl: c.productsIndexUrl,
        existingVariantsBootstrap: true,
        existingColorPhotos: c.existingColorPhotos && typeof c.existingColorPhotos === 'object' ? c.existingColorPhotos : {},
        defaultSuccessMessage: 'Produto atualizado com sucesso!',
    });
});
