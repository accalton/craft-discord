import React, { useContext, useEffect, useState } from 'react';
import { DiscordContext } from './config/discord-context';
import ColorPicker from './Fields/ColorPicker';
import Embed from './Fields/Embed';
import Select from './Fields/Select';
import TextArea from './Fields/TextArea';
import TextInput from './Fields/TextInput';

const Discord = () => {
    const [state, setState] = useState({ guild: '' });
    const [channels, setChannels] = useState([]);
    const [emojis, setEmojis] = useState([]);
    const [guilds, setGuilds] = useState([]);
    const [roles, setRoles] = useState([]);
    const [validSubmission, setValidSubmission] = useState(false);

    const getChannels = event => {
        setChannels([]);

        if (state.guild) {
            axios.get('/admin/actions/discordbot/guild/channels', {
                params: {
                    guildId: state.guild
                }
            }).then(response => {
                setChannels(response.data);
            });
        }
    }

    const getEmojis = () => {
        setEmojis([]);

        if (state.guild) {
            axios.get('/admin/actions/discordbot/guild/emojis', {
                params: {
                    guildId: state.guild
                }
            }).then(response => {
                setEmojis(response.data);
            });
        }
    }

    const getGuilds = () => {
        axios.get('/admin/actions/discordbot/member/guilds')
            .then(response => {
                setGuilds(response.data);
            });
    }

    const setGuild = event => {
        let guild = event.target.value;
        setState({ guildId: guild });
    }

    const getRoles = () => {
        setRoles([]);

        if (state.guild) {
            axios.get('/admin/actions/discordbot/guild/roles', {
                params: {
                    guildId: state.guild
                }
            }).then(response => {
                setRoles(response.data);
            });
        }
    }

    const onChange = event => {
        let newState = {...state};
        newState[event.target.name] = event.target.value;
        setState(newState);
    }

    const validateSubmission = () => {
        if (
            state.guild &&
            state.channel &&
            state.title
        ) {
            setValidSubmission(true);
        } else {
            setValidSubmission(false);
        }
    }

    useEffect(() => getGuilds(), []);
    useEffect(() => {
        getChannels();
        getEmojis();
        getRoles();
    }, [state.guild]);

    useEffect(() => {
        validateSubmission();
    }, [state]);

    return (
        <DiscordContext.Provider value={[state, setState]}>
            <form className="flex-fields" method="POST" action="/admin/actions/discordbot/discord/post">
                <TextInput name="title" label="Title" width="100" onChange={onChange} />
                <Select name="guild" label="Guild" width="50" options={guilds} onChange={onChange} />
                <Select name="channel" label="Channel" width="50" options={channels} onChange={onChange} />
                <hr />
                <ColorPicker name="color" label="Color" width="25" onChange={onChange} />
                <TextArea name="description" label="Description" width="75" onChange={onChange} />
                <hr />
                <Embed name="roleReacts" label="Role Reacts" width="100" roles={roles} emojis={emojis} onChange={onChange} />
                <button
                    className={`btn submit width-25${!validSubmission ? ' disabled' : ''}`}
                    type="submit"
                    disabled={!validSubmission}
                >Submit</button>
            </form>
        </DiscordContext.Provider>
    );
}

export default Discord;