import React, { useState } from "react";

export default function ListaPratos({
  pratos,
  onVoltar,
  onEditar,
  onExcluir,
  onNovo,
}) {
  const [editando, setEditando] = useState(null);
  const [form, setForm] = useState({});
  const [busca, setBusca] = useState("");

  const handleEditar = (prato) => {
    setEditando(prato.id);
    setForm(prato);
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
    if (window.confirm(`Deseja realmente excluir o prato ${nome}?`)) {
      onExcluir(id);
    }
  };

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const pratosFiltrados = pratos.filter(
    (p) =>
      p.nome.toLowerCase().includes(busca.toLowerCase()) ||
      p.ingredientes.toLowerCase().includes(busca.toLowerCase())
  );

  return (
    <div className="min-h-screen bg-gradient-to-br from-green-100 to-green-300 p-6">
      <div className="max-w-6xl mx-auto">
        {/* Header */}
        <div className="bg-white rounded-2xl shadow-xl p-6 mb-6">
          <div className="flex items-center justify-between mb-4">
            <button
              onClick={onVoltar}
              className="text-green-700 hover:text-green-900 font-semibold"
            >
              ← Voltar ao Dashboard
            </button>
            <h1 className="text-3xl font-bold text-green-700">🍴 Cardápio</h1>
            <button
              onClick={onNovo}
              className="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition"
            >
              ➕ Novo
            </button>
          </div>

          {/* Barra de Busca */}
          <input
            type="text"
            placeholder="Buscar por nome ou ingredientes..."
            value={busca}
            onChange={(e) => setBusca(e.target.value)}
            className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        {/* Lista de Pratos */}
        {pratosFiltrados.length === 0 ? (
          <div className="bg-white rounded-2xl shadow-xl p-12 text-center">
            <p className="text-gray-500 text-xl">
              {busca
                ? "Nenhum prato encontrado com esses critérios."
                : "Nenhum prato cadastrado ainda."}
            </p>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {pratosFiltrados.map((prato) => (
              <div
                key={prato.id}
                className="bg-white rounded-2xl shadow-xl p-6"
              >
                {editando === prato.id ? (
                  // Modo de Edição
                  <div className="space-y-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Nome do Prato
                      </label>
                      <input
                        type="text"
                        name="nome"
                        value={form.nome}
                        onChange={handleChange}
                        className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Ingredientes
                      </label>
                      <textarea
                        name="ingredientes"
                        value={form.ingredientes}
                        onChange={handleChange}
                        rows="3"
                        className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Valor (R$)
                      </label>
                      <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="valor"
                        value={form.valor}
                        onChange={handleChange}
                        className="w-full mt-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                      />
                    </div>
                    <div className="flex gap-2">
                      <button
                        onClick={() => handleSalvar(prato.id)}
                        className="flex-1 bg-green-600 text-white p-2 rounded-lg font-semibold hover:bg-green-700 transition text-sm"
                      >
                        ✓ Salvar
                      </button>
                      <button
                        onClick={handleCancelar}
                        className="flex-1 bg-gray-500 text-white p-2 rounded-lg font-semibold hover:bg-gray-600 transition text-sm"
                      >
                        ✗ Cancelar
                      </button>
                    </div>
                  </div>
                ) : (
                  // Modo de Visualização
                  <div>
                    <div className="mb-4">
                      <h3 className="text-xl font-bold text-gray-800 mb-2">
                        {prato.nome}
                      </h3>
                      <div className="mb-3">
                        <p className="text-sm font-semibold text-gray-600 mb-1">
                          Ingredientes:
                        </p>
                        <p className="text-sm text-gray-600 italic">
                          {prato.ingredientes}
                        </p>
                      </div>
                      <div className="bg-green-100 rounded-lg p-3 text-center">
                        <p className="text-2xl font-bold text-green-700">
                          R$ {parseFloat(prato.valor).toFixed(2)}
                        </p>
                      </div>
                    </div>
                    <div className="flex gap-2">
                      <button
                        onClick={() => handleEditar(prato)}
                        className="flex-1 bg-yellow-500 text-white p-2 rounded-lg font-semibold hover:bg-yellow-600 transition text-sm"
                      >
                        ✏️ Editar
                      </button>
                      <button
                        onClick={() => handleExcluir(prato.id, prato.nome)}
                        className="flex-1 bg-red-600 text-white p-2 rounded-lg font-semibold hover:bg-red-700 transition text-sm"
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
