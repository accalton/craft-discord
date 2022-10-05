import React, { useContext, useState } from 'react';
import RoleEmoji from '../molecules/RoleEmoji';

const Matrix = props => {
    const { name, label, width, roles, emojis } = props;
    const [rows, setRows] = useState([]);
    const [rowCount, setRowCount] = useState(0);

    const addRow = () => {
        setRowCount(rowCount + 1);
        setRows(rows.concat([{
            name: 'foo',
            label: 'Foobar',
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
                            return <RoleEmoji
                                name={row.name}
                                label={row.label}
                                id={row.index}
                                key={row.index}
                                roles={roles}
                                emojis={emojis}
                                removeRow={removeRow}
                            />
                        })}
                    </div>
                    <div className="buttons last">
                        <div className="btngroup">
                            <button type="button" className="btn dashed add icon" onClick={addRow}>Row</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Matrix;