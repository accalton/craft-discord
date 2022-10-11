import React, { useContext, useState } from 'react';
import EmojiText from './embed/EmojiText';
import RoleEmoji from './embed/RoleEmoji';

const Embed = props => {
    const { name, label, width, type, roles, emojis, onChange } = props;
    const [rows, setRows] = useState([]);
    const [rowCount, setRowCount] = useState(0);

    const addRoleReact = () => {
        setRowCount(rowCount + 1);
        setRows(rows.concat([{
            index: rowCount
        }]));
    }

    const removeRow = id => {
        setRows(rows.filter(row => row.index !== id));
    }

    return (
        <div className={`field width-${width}`}>
            <div className="heading">
                <label htmlFor={name}>{label}</label>
            </div>
            <div className="input ltr">
                <div className="matrix matrix-field" style={{ position: 'relative' }}>
                    <div className="blocks">
                        {rows.map(row => {
                            switch (type) {
                                case 'RoleEmoji':
                                    return <RoleEmoji
                                        id={row.index}
                                        key={row.index}
                                        roles={roles}
                                        emojis={emojis}
                                        removeRow={removeRow}
                                        onChange={onChange}
                                    />
                                    break;
                                case 'EmojiText':
                                    return <EmojiText
                                        id={row.index}
                                        key={row.index}
                                        emojis={emojis}
                                        removeRow={removeRow}
                                        onChange={onChange}
                                    />
                                    break;
                            }
                        })}
                    </div>
                    <div className="buttons last">
                        <div className="btngroup">
                            <button type="button" className="btn dashed add icon" onClick={addRoleReact}>Role React</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Embed;