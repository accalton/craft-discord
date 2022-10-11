import React from 'react';
import Select from '../Select';

const RoleEmoji = props => {
    const { emojis, id, onChange, removeRow, roles } = props;

    const handleClick = () => {
        removeRow(id);
    }

    return (
        <div className="matrixblock">
            <div className="titlebar">
                <div className="blocktype">Role React</div>
            </div>
            <div className="actions">
                <a className="error" data-icon="remove" type="button" role="button" onClick={handleClick}>Delete</a>
            </div>
            <div className="fields">
                <div className="flex-fields">
                    <Select name={`role[${id}]`} label="Role" width="50" options={roles} onChange={onChange} />
                    <Select name={`emoji[${id}]`} label="Emoji" width="50" options={emojis} onChange={onChange} />
                </div>
            </div>
        </div>
    );
}

export default RoleEmoji;