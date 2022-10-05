import React, { useContext, useEffect, useState } from 'react';
import { DiscordContext } from '../config/discord-context';
import Select from '../Fields/Select';

const RoleEmoji = props => {
    const { roles, emojis, id, removeRow } = props;
    const state = useContext(DiscordContext);

    const handleClick = () => {
        removeRow(id);
    }

    return (
        <div className="matrixblock">
            <div className="titlebar">
                <div className="blocktype">Row</div>
            </div>
            <div className="actions">
                <a className="error" data-icon="remove" type="button" role="button" onClick={handleClick}>Delete</a>
            </div>
            <div className="fields">
                <div className="flex-fields">
                    <Select name={`role[${id}]`} label="Role" width="50" options={roles} />
                    <Select name={`emoji[${id}]`} label="Emoji" width="50" options={emojis} />
                </div>
            </div>
        </div>
    );
}

export default RoleEmoji;