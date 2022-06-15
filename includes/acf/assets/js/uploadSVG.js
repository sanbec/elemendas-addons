const { __, _x, _n, _nx } = wp.i18n;
const input = document.querySelector('input#elm_icon_files');
const preview = document.querySelector('div#elm_icon_preview');
const uploadButton = document.querySelector('input#icons-upload');

input.addEventListener('change', updateImageDisplay);

function updateImageDisplay() {
    while(preview.firstChild) {
        preview.removeChild(preview.firstChild);
    }

    if(input.files.length === 0) {
        const iconDesc = document.createElement('p');
        iconDesc.textContent = __('No files currently selected for upload','elemendas-addons');
        preview.appendChild(iconDesc);
        uploadButton.style.visibility = 'hidden';
    } else {
        const list = document.createElement('ol');
        preview.appendChild(list);

        for(const file of input.files) {
            const listItem = document.createElement('li');
            const iconDesc = document.createElement('div');
            const iconName = document.createElement('p');
            const iconSize = document.createElement('p');
            iconDesc.className = 'elm_icon_desc ';
            iconName.textContent = `${file.name}`;
            iconSize.textContent = `${returnFileSize(file.size)}`;
            iconDesc.appendChild(iconName);
            iconDesc.appendChild(iconSize);

            if(validFileType(file)) {
                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                listItem.appendChild(image);
                listItem.appendChild(iconDesc);
            } else {
                const badFilename = document.createElement('p');
                badFilename.textContent = __('Invalid file type. Update your selection.','elemendas-addons');
                iconDesc.appendChild(badFilename);
                listItem.appendChild(iconDesc);
            }
            list.appendChild(listItem);
        }
        uploadButton.style.visibility = 'visible';
    }
}

// https://developer.mozilla.org/en-US/docs/Web/Media/Formats/Image_types
const fileTypes = [
    'image/svg+xml'
];

function validFileType(file) {
    return fileTypes.includes(file.type);
}

function returnFileSize(number) {
    if(number < 1024) {
    return number + 'bytes';
    } else if(number > 1024 && number < 1048576) {
    return (number/1024).toFixed(1) + 'KB';
    } else if(number > 1048576) {
    return (number/1048576).toFixed(1) + 'MB';
    }
}
