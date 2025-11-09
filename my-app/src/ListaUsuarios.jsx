import React, { useState } from "react";

export default function ListaUsuarios({
  usuarios,
  onVoltar,
  onEditar,
  onExcluir,
  onNovo,
}) {
  const [editando, setEditando] = useState(null);
  const [form, setForm] = useState({});
  const [busca, setBusca] = useState("");

  const handleEditar = (usuario) => {
    setEditando(usuario.id);
    setForm(usuario);
  };

  const handleSalvar = (id) => {
    onEditar(id, form);
    setEditando(null);
    setForm({});
  };

  const handleCancelar = () => {
    setEditando(null);
    setForm({});
  };

  const handleExcluir = (id, nome) => {
    if (window.confirm(`Deseja realmente excluir o usuário ${nome}?`)) {
      onExcluir(id);
    }
  };

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const formatCPF = (value) => {
    return value
      .replace(/\D/g, "")
      .replace(/(\d{3})(\d)/, "$1.$2")
      .replace(/(\d{3})(\d)/, "$1.$2")
      .replace(/(\d{3})(\d{1,2})/, "$1-$2")
      .replace(/(-\d{2})\d+?$/, "$1");
  };

  const handleCPFChange = (e) => {
    const formatted = formatCPF(e.target.value);
    setForm({ ...form, cpf: formatted });
  };

  const usuariosFiltrados = usuarios.filter(
    (u) =>
      u.nome.toLowerCase().includes(busca.toLowerCase()) ||
      u.cpf.includes(busca) ||
      u.endereco.toLowerCase().includes(busca.toLowerCase())
  );

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-100 to-blue-300 p-6">
      <div className="max-w-6xl mx-auto">
        {/* Header */}
        <div className="bg-white rounded-2xl shadow-xl p-6 mb-6">
          <div className="flex items-center justify-between mb-4">
            <button
              onClick={onVoltar}
              className="text-blue-700 hover:text-blue-900 font-semibold"
            >
              ← Voltar ao Dashboard
            </button>
            <h1 className="text-3xl font-bold text-blue-700">
              👥 Lista de Usuários
            </h1>
            <button
              onClick={onNovo}
              className="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition"
            >
              ➕ Novo
            </button>
          </div>

          {/* Barra de Busca */}
          <input
            type="text"
            placeholder="Buscar por nome, CPF ou endereço..."
            value={busca}
            onChange={(e) => setBusca(e.target.value)}
            className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
        </div>

        {/* Lista de Usuários */}
        {usuariosFiltrados.length === 0 ? (
          <div className="bg-white rounded-2xl shadow-xl p-12 text-center">
            <p className="text-gray-500 text-xl">
              {busca
                ? "Nenhum usuário encontrado com esses critérios."
                : "Nenhum usuário cadastrado ainda."}
            </p>
          </div>
        ) : (
          <div className="space-y-4">
            {usuariosFiltrados.map((usuario) => (
              <div
                key={usuario.id}
                className="bg-white rounded-2xl shadow-xl p-6"
              >
                {editando === usuario.id ? (
                  // Modo de Edição
                  <div className="space-y-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Nome
                      </label>
                      <input
                        type="text"
                        name="nome"
                        value={form.nome}
                        onChange={handleChange}
                        className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Data de Nascimento
                      </label>
                      <input
                        type="date"
                        name="dataNascimento"
                        value={form.dataNascimento}
                        onChange={handleChange}
                        className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        CPF
                      </label>
                      <input
                        type="text"
                        name="cpf"
                        value={form.cpf}
                        onChange={handleCPFChange}
                        maxLength="14"
                        className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Endereço
                      </label>
                      <input
                        type="text"
                        name="endereco"
                        value={form.endereco}
                        onChange={handleChange}
                        className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                      />
                    </div>
                    <div className="flex gap-2">
                      <button
                        onClick={() => handleSalvar(usuario.id)}
                        className="flex-1 bg-green-600 text-white p-2 rounded-lg font-semibold hover:bg-green-700 transition"
                      >
                        ✓ Salvar
                      </button>
                      <button
                        onClick={handleCancelar}
                        className="flex-1 bg-gray-500 text-white p-2 rounded-lg font-semibold hover:bg-gray-600 transition"
                      >
                        ✗ Cancelar
                      </button>
                    </div>
                  </div>
                ) : (
                  // Modo de Visualização
                  <div>
                    <div className="mb-4">
                      <h3 className="text-2xl font-bold text-gray-800">
                        {usuario.nome}
                      </h3>
                      <div className="mt-2 space-y-1 text-gray-600">
                        <p>
                          <strong>CPF:</strong> {usuario.cpf}
                        </p>
                        <p>
                          <strong>Data de Nascimento:</strong>{" "}
                          {new Date(
                            usuario.dataNascimento + "T00:00:00"
                          ).toLocaleDateString("pt-BR")}
                        </p>
                        <p>
                          <strong>Endereço:</strong> {usuario.endereco}
                        </p>
                      </div>
                    </div>
                    <div className="flex gap-2">
                      <button
                        onClick={() => handleEditar(usuario)}
                        className="flex-1 bg-yellow-500 text-white p-2 rounded-lg font-semibold hover:bg-yellow-600 transition"
                      >
                        ✏️ Editar
                      </button>
                      <button
                        onClick={() => handleExcluir(usuario.id, usuario.nome)}
                        className="flex-1 bg-red-600 text-white p-2 rounded-lg font-semibold hover:bg-red-700 transition"
                      >
                        🗑️ Excluir
                      </button>
                    </div>
                  </div>
                )}
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
