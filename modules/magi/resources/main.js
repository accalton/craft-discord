import axios from 'axios';

const setDropdowns = (guildId, type) => {
    let optionsHTML = '<option value="">---</option>';
    const dropdowns = document.querySelectorAll('[data-field-type="discord-' + type +'"]');

    if (guildId) {
        axios.get('/admin/actions/magi/discord/' + type, {
            params: {
                guildId: guildId
            }
        }).then(response => {
            optionsHTML += response.data.map(option => {
                return '<option value="' + option.id + '">' + option.name + '</option>';
            });

            for (const dropdown of dropdowns) {
                dropdown.innerHTML = optionsHTML;
            }
        })
    } else {
        for (const dropdown of dropdowns) {
            dropdown.innerHTML = optionsHTML;
        }
    }
}

document.addEventListener('change', function (e) {
    if (e.target.name === 'fields[guild]') {
        const guildId = e.target.value;
        setDropdowns(guildId, 'channels-text');
        setDropdowns(guildId, 'channels-voice');
        setDropdowns(guildId, 'emojis');
        setDropdowns(guildId, 'roles');
    }
});
