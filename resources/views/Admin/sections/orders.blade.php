<div x-data="orderApp" x-init="updateCurrentTime()" class="main-wrapper">
    
    <div x-show="toastShow" x-transition 
         style="position: fixed; top: 20px; right: 20px; background: #1e293b; color: white; padding: 12px 20px; border-radius: 8px; font-size: 13px; z-index: 3000; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);" 
         x-text="toastMsg">
    </div>

    <div x-show="modalShow" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="modal-overlay" 
         style="display: none;"
         @click.self="closeModal()">
        
        <div class="modal-container" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            <div class="modal-header-enhanced">
                <div class="modal-header-left">
                    <div class="modal-badge-id" x-text="selectedOrder?.id"></div>
                    <div class="modal-subtitle-date">
                        Placed on <span class="font-semibold" x-text="selectedOrder?.date"></span>
                    </div>
                </div>
                <div class="modal-header-actions">
                    <button class="modal-action-toggle-btn" :class="isModalEditing ? 'save-mode' : 'edit-mode'" @click="toggleModalEdit()">
                        <template x-if="!isModalEditing">
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit Order Details
                            </span>
                        </template>
                        <template x-if="isModalEditing">
                            <span style="display: flex; align-items: center; gap: 6px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                Save Changes
                            </span>
                        </template>
                    </button>
                    
                    <button class="close-modal-btn" @click="closeModal()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="modal-body">
                
                <div class="status-control-panel">
                    <div class="panel-section-label">Order Fulfillment & Logistics</div>
                    <div class="status-flex-row">
                        <div class="status-display-box">
                            <template x-if="!isModalEditing">
                                <span class="status-tag" :class="'badge-'+selectedOrder?.status.toLowerCase()" x-text="selectedOrder?.status"></span>
                            </template>
                            <template x-if="isModalEditing">
                                <select class="modern-form-select" x-model="selectedOrder.status">
                                    <option value="Pending">Pending</option>
                                    <option value="Shipped">Shipped</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </template>
                        </div>
                        <div class="status-description-box">
                            <template x-if="!isModalEditing">
                                <span class="system-note-text" x-text="selectedOrder?.status === 'Delivered' ? 'Package received by customer' : selectedOrder?.status === 'Shipped' ? 'In transit via Printify Logistics' : selectedOrder?.status === 'Cancelled' ? 'Order voided' : 'Awaiting production batch'"></span>
                            </template>
                            <template x-if="isModalEditing">
                                <div style="width: 100%;">
                                    <label class="compact-field-label">Delivery Note / Internal Info</label>
                                    <input type="text" class="modern-form-input" x-model="selectedOrder.deliveryNote" placeholder="e.g. In transit via Printify Logistics">
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <div class="tracking-container">
                        <span class="tracking-lbl">Tracking Number:</span>
                        <template x-if="!isModalEditing">
                            <span class="tracking-val" x-text="selectedOrder?.trackingNum || 'Not Assigned Yet'"></span>
                        </template>
                        <template x-if="isModalEditing">
                            <input type="text" class="modern-form-input tracking-input" x-model="selectedOrder.trackingNum" placeholder="e.g. PRNT-TRK-1001XYZ">
                        </template>
                    </div>
                </div>

                <div class="modal-main-grid-layout">
                    
                    <div class="form-sections-stack">
                        
                        <div class="professional-card-section">
                            <h4 class="card-section-heading">Customer & Shipping Info</h4>
                            <div class="card-form-body">
                                <div class="form-field-group">
                                    <label class="field-input-label">Customer Name</label>
                                    <div x-show="!isModalEditing" class="static-value-text font-bold text-dark" x-text="selectedOrder?.name"></div>
                                    <input x-show="isModalEditing" type="text" class="modern-form-input" x-model="selectedOrder.name">
                                </div>
                                
                                <div class="form-field-grid-2col">
                                    <div class="form-field-group">
                                        <label class="field-input-label">Email Address</label>
                                        <div x-show="!isModalEditing" class="static-value-text" x-text="selectedOrder?.email"></div>
                                        <input x-show="isModalEditing" type="email" class="modern-form-input" x-model="selectedOrder.email">
                                    </div>
                                    <div class="form-field-group">
                                        <label class="field-input-label">Phone Number</label>
                                        <div x-show="!isModalEditing" class="static-value-text" x-text="selectedOrder?.phone"></div>
                                        <input x-show="isModalEditing" type="text" class="modern-form-input" x-model="selectedOrder.phone">
                                    </div>
                                </div>

                                <div class="form-field-group">
                                    <label class="field-input-label">Shipping Address</label>
                                    <div x-show="!isModalEditing" class="static-value-text line-clamp-address" x-text="selectedOrder?.address"></div>
                                    <textarea x-show="isModalEditing" rows="2" class="modern-form-textarea" x-model="selectedOrder.address"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="professional-card-section">
                            <h4 class="card-section-heading">Payment Details</h4>
                            <div class="card-form-body">
                                <div class="form-field-grid-2col">
                                    <div class="form-field-group">
                                        <label class="field-input-label">Payment Method</label>
                                        <div x-show="!isModalEditing" class="static-value-text font-medium" x-text="selectedOrder?.paymentMethod"></div>
                                        <select x-show="isModalEditing" class="modern-form-select" x-model="selectedOrder.paymentMethod">
                                            <option value="GCash">GCash</option>
                                            <option value="Maya">Maya</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cash on Delivery">Cash on Delivery</option>
                                        </select>
                                    </div>
                                    <div class="form-field-group">
                                        <label class="field-input-label">Payment Status</label>
                                        <div x-show="!isModalEditing">
                                            <span class="payment-status-indicator" :style="selectedOrder?.paymentStatus === 'Paid' ? 'color: #16a34a;' : 'color: #dc2626;'" x-text="selectedOrder?.paymentStatus || 'Paid'"></span>
                                        </div>
                                        <select x-show="isModalEditing" class="modern-form-select" x-model="selectedOrder.paymentStatus">
                                            <option value="Paid">Paid</option>
                                            <option value="Unpaid">Unpaid</option>
                                            <option value="Refunded">Refunded</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-field-group">
                                    <label class="field-input-label">Transaction ID</label>
                                    <div x-show="!isModalEditing" class="static-value-text code-font" x-text="selectedOrder?.txnId"></div>
                                    <input x-show="isModalEditing" type="text" class="modern-form-input code-font" x-model="selectedOrder.txnId">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="items-summary-container">
                        <h4 class="card-section-heading">Items Ordered</h4>
                        <div class="professional-table-card">
                            <table class="modern-items-table">
                                <thead>
                                    <tr>
                                        <th>Product Information</th>
                                        <th style="text-align: center; width: 60px;">Qty</th>
                                        <th style="text-align: right; width: 100px;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in selectedOrder?.items" :key="index">
                                        <tr>
                                            <td>
                                                <div class="item-title-text" x-text="item.title"></div>
                                                <div class="item-sku-sub" x-text="'SKU: ' + item.sku"></div>
                                            </td>
                                            <td style="text-align: center;">
                                                <span x-show="!isModalEditing" class="font-medium text-dark" x-text="item.qty"></span>
                                                <input x-show="isModalEditing" type="number" class="modern-form-input text-center compact-padding" x-model.number="item.qty" @input="recalculateOrderTotals()">
                                            </td>
                                            <td style="text-align: right;" class="font-medium text-dark">
                                                <span x-show="!isModalEditing" x-text="item.price"></span>
                                                <input x-show="isModalEditing" type="text" class="modern-form-input text-right compact-padding" x-model="item.price" @input="recalculateOrderTotals()">
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            
                            <div class="modern-financial-summary">
                                <div class="financial-row">
                                    <span class="financial-lbl">Subtotal</span>
                                    <span class="financial-val">
                                        <span x-show="!isModalEditing" x-text="selectedOrder?.subtotal"></span>
                                        <input x-show="isModalEditing" type="text" class="modern-form-input text-right summary-field" x-model="selectedOrder.subtotal">
                                    </span>
                                </div>
                                <div class="financial-row">
                                    <span class="financial-lbl">Shipping Fee</span>
                                    <span class="financial-val">
                                        <span x-show="!isModalEditing" x-text="selectedOrder?.shippingFee"></span>
                                        <input x-show="isModalEditing" type="text" class="modern-form-input text-right summary-field" x-model="selectedOrder.shippingFee">
                                    </span>
                                </div>
                                <div class="financial-row-grand-total">
                                    <span class="grand-total-lbl">Grand Total</span>
                                    <span class="grand-total-val">
                                        <span x-show="!isModalEditing" x-text="selectedOrder?.total"></span>
                                        <input x-show="isModalEditing" type="text" class="modern-form-input text-right summary-field font-bold" x-model="selectedOrder.total">
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            <label class="field-input-label" style="margin-bottom: 6px; display: block;">Internal Admin Notes (Private)</label>
                            <textarea rows="2" class="modern-form-textarea" x-model="selectedOrder.adminNotes" :placeholder="isModalEditing ? 'Type notes here that only admins can see...' : 'No internal notes saved.'" :disabled="!isModalEditing"></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-blue: #4F46E5;
            --primary-blue-hover: #4338ca;
            --border-color: #cbd5e1; 
            --bg-light: #f4f7fa;
            --danger-red: #ef4444;
            --text-dark: #1e293b;
            --text-secondary: #64748b;
            --emerald-green: #10b981;
        }

        /* Original Layout CSS (Do Not Touch Background Wrapper Configurations) */
        .main-wrapper { width: 100%; max-width: 1150px; margin: 0 auto; padding: 20px; }
        .header-left-date { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
        .giant-title { font-family: 'DM Serif Display', serif; font-size: 38px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 30px; color: var(--text-dark); }
        .dashboard-grid { display: grid; grid-template-columns: 1fr 280px; gap: 20px; align-items: start; }
        .main-column { display: flex; flex-direction: column; gap: 15px; }
        .box-container { background: white; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
        .filter-header-content { padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .left-filters { display: flex; align-items: center; gap: 20px; }
        .section-title-btn { font-family: 'DM Serif Display', serif; font-size: 20px; font-style: italic; color: #1e293b; background: none; border: none; cursor: pointer; }
        .filter-label-bold { font-size: 15px; font-weight: 800; color: var(--text-dark); }
        .status-select { font-size: 12px; font-weight: 800; color: var(--primary-blue); border: 1px solid var(--border-color); background: #f8fafc; padding: 6px 10px; border-radius: 6px; text-transform: uppercase; outline: none; }
        .mini-input { padding: 6px 10px; border-radius: 6px; border: 1px solid var(--border-color); font-size: 12px; background: #f8fafc; }
        .search-wrapper { position: relative; display: flex; align-items: center; }
        .search-wrapper input { padding: 8px 12px 8px 35px; border-radius: 6px; border: 1px solid var(--border-color); font-size: 13px; width: 200px; background: #f8fafc; outline: none; }
        .search-wrapper svg { position: absolute; left: 10px; color: #94a3b8; }
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table th { font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; padding: 15px 20px; text-align: left; background: #f8fafc; border-bottom: 2px solid var(--border-color); }
        .main-table td { padding: 15px 20px; font-size: 14px; color: #64748b; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        .order-id-link { color: var(--primary-blue); font-weight: 800; text-decoration: none; transition: text-decoration 0.2s ease; }
        .order-id-link:hover { text-decoration: underline; }
        .customer-name { font-weight: 700; color: var(--text-dark); }
        .edit-input { padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 13px; outline: none; background: #f8fafc; width: 100%; }
        .status-text { font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .status-pending { color: #ea580c; }
        .status-shipped { color: #0284c7; }
        .status-delivered { color: #16a34a; }
        .status-cancelled { color: #dc2626; }
        .action-group { display: flex; gap: 12px; justify-content: flex-end; }
        .action-btn { background: transparent; border: none; cursor: pointer; color: #cbd5e1; }
        .action-btn:hover { color: var(--primary-blue); }
        .action-btn.delete:hover { color: var(--danger-red); }
        .pagination { display: flex; justify-content: center; align-items: center; gap: 8px; padding: 20px; }
        .pg-btn { padding: 5px 10px; font-size: 12px; font-weight: 800; color: #64748b; background: none; border: none; cursor: pointer; text-transform: uppercase; }
        .pg-btn:disabled { opacity: 0.4; cursor: not-allowed; }
        .pg-num { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid var(--border-color); font-size: 12px; font-weight: 700; cursor: pointer; }
        .pg-num.active { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
        .stats-box { padding: 20px 45px; }
        .stats-title { font-family: 'DM Serif Display', serif; font-size: 20px; font-style: italic; border-bottom: 2px solid #94a3b8; padding-bottom: 10px; margin-bottom: 15px; }
        .stat-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #cbd5e1; }
        .stat-label { font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; }
        .stat-val { font-size: 18px; font-weight: 800; color: var(--text-dark); }

        /* ==========================================================================
           NEW STANDARD PROFESSIONAL POPUP/MODAL UI DESIGN SYSTEM STYLES
           ========================================================================== */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 2500; padding: 20px; }
        .modal-container { background: #ffffff; width: 100%; max-width: 950px; border-radius: 14px; box-shadow: 0 25px 60px -15px rgba(0,0,0,0.3); max-height: 92vh; overflow-y: auto; display: flex; flex-direction: column; border: 1px solid #e2e8f0; font-family: system-ui, -apple-system, sans-serif; }
        
        /* Header Upgrade */
        .modal-header-enhanced { padding: 22px 28px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #fafafa; }
        .modal-header-left { display: flex; flex-direction: column; gap: 4px; }
        .modal-badge-id { font-family: 'DM Serif Display', serif; font-size: 26px; font-weight: 700; color: var(--text-dark); letter-spacing: 0.5px; }
        .modal-subtitle-date { font-size: 13px; color: var(--text-secondary); }
        .modal-header-actions { display: flex; align-items: center; gap: 14px; }
        
        /* Modernized Header Button for Admin toggle */
        .modal-action-toggle-btn { padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 6px; cursor: pointer; transition: all 0.2s ease-in-out; text-transform: uppercase; letter-spacing: 0.03em; border: 1px solid transparent; }
        .modal-action-toggle-btn.edit-mode { background: #ffffff; color: var(--primary-blue); border-color: #cbd5e1; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .modal-action-toggle-btn.edit-mode:hover { background: #f8fafc; border-color: #94a3b8; }
        .modal-action-toggle-btn.save-mode { background: var(--emerald-green); color: white; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); }
        .modal-action-toggle-btn.save-mode:hover { background: #059669; }

        .close-modal-btn { background: none; border: none; color: #94a3b8; cursor: pointer; display: flex; align-items: center; justify-content: center; border-radius: 50%; padding: 6px; transition: all 0.2s; }
        .close-modal-btn:hover { background: #e2e8f0; color: var(--text-dark); }
        
        /* Modal Content Structure */
        .modal-body { padding: 28px; background: #ffffff; }
        
        /* Fulfillment Custom Bar Panel */
        .status-control-panel { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 22px; margin-bottom: 24px; }
        .panel-section-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 12px; letter-spacing: 0.05em; }
        .status-flex-row { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; margin-bottom: 12px; }
        .status-display-box { min-width: 140px; }
        .status-description-box { flex: 1; min-width: 240px; }
        
        /* Badge Design Variants */
        .status-tag { display: inline-block; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em; }
        .badge-pending { background: #ffedd5; color: #ea580c; border: 1px solid #fed7aa; }
        .badge-shipped { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
        .badge-delivered { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        
        .system-note-text { font-size: 13px; color: var(--text-secondary); font-style: italic; }
        
        /* Logistics Tracking Text */
        .tracking-container { border-top: 1px dashed #e2e8f0; padding-top: 12px; margin-top: 4px; display: flex; align-items: center; gap: 8px; font-size: 12px; }
        .tracking-lbl { font-weight: 600; color: #64748b; }
        .tracking-val { font-family: monospace; font-size: 13px; font-weight: 700; color: var(--text-dark); background: #f1f5f9; padding: 2px 6px; border-radius: 4px; }
        .tracking-input { max-width: 250px; display: inline-block; }

        /* Dashboard Internal Form Structure Grid */
        .modal-main-grid-layout { display: grid; grid-template-columns: 1.1fr 1fr; gap: 28px; }
        @media (max-width: 850px) { .modal-main-grid-layout { grid-template-columns: 1fr; } }
        
        .form-sections-stack { display: flex; flex-direction: column; gap: 20px; }
        .professional-card-section { border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; background: #ffffff; }
        .card-section-heading { font-size: 12px; font-weight: 800; text-transform: uppercase; color: #475569; background: #f8fafc; padding: 12px 18px; margin: 0; border-bottom: 1px solid #e2e8f0; letter-spacing: 0.03em; }
        .card-form-body { padding: 18px; display: flex; flex-direction: column; gap: 14px; }
        
        /* Input & Controls Standardization styling */
        .form-field-group { display: flex; flex-direction: column; gap: 5px; }
        .form-field-grid-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .field-input-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .compact-field-label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 3px; display: block; }
        
        .static-value-text { font-size: 13.5px; color: #475569; padding: 6px 0; min-height: 20px; }
        .static-value-text.font-bold { font-weight: 600; }
        .static-value-text.font-medium { font-weight: 500; }
        .static-value-text.text-dark { color: var(--text-dark); }
        .line-clamp-address { line-height: 1.5; }
        .code-font { font-family: monospace; font-size: 12.5px; color: #334155; }
        .payment-status-indicator { font-weight: 700; font-size: 13px; text-transform: uppercase; padding: 6px 0; display: inline-block; }
        
        /* Clean Inputs Schema */
        .modern-form-input, .modern-form-select, .modern-form-textarea {
            width: 100%;
            font-size: 13px;
            color: var(--text-dark);
            padding: 8px 12px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            outline: none;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .modern-form-input:focus, .modern-form-select:focus, .modern-form-textarea:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1), inset 0 1px 2px rgba(0,0,0,0.02);
        }
        .modern-form-textarea { resize: vertical; font-family: inherit; }
        .modern-form-input.compact-padding { padding: 4px 8px; }
        .summary-field { max-width: 110px; display: inline-block; padding: 4px 6px; text-align: right; }
        
        /* Items Table Right Side Card Structure */
        .items-summary-container { display: flex; flex-direction: column; }
        .professional-table-card { border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; background: #ffffff; display: flex; flex-direction: column; justify-content: space-between; height: 100%; max-height: 420px; }
        
        .modern-items-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .modern-items-table th { background: #fafafa; text-align: left; padding: 12px 16px; font-weight: 700; color: #475569; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
        .modern-items-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        
        .item-title-text { font-weight: 600; color: var(--text-dark); line-height: 1.4; }
        .item-sku-sub { font-size: 11px; color: var(--text-secondary); margin-top: 3px; font-family: monospace; }
        
        /* Financial Breakdown Alignment Area */
        .modern-financial-summary { background: #fafafa; padding: 18px; border-top: 1px solid #e2e8f0; }
        .financial-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; font-size: 13px; color: var(--text-secondary); }
        .financial-lbl { font-weight: 500; }
        .financial-val { font-weight: 600; color: var(--text-dark); }
        
        .financial-row-grand-total { display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; margin-top: 10px; padding-top: 12px; }
        .grand-total-lbl { font-size: 14px; font-weight: 800; color: var(--text-dark); text-transform: uppercase; }
        .grand-total-val { font-size: 18px; font-weight: 800; color: var(--text-dark); }
        
        /* Helper Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>

    <header class="top-header">
        <div class="header-left-date" x-text="currentTime"></div>
    </header>

    <h1 class="giant-title">Order Management</h1>

    <div class="dashboard-grid">
        <div class="main-column">
            <div class="box-container filter-header-content">
                <div class="left-filters">
                    <button class="section-title-btn" @click="resetFilters">All Orders</button>
                    <div class="filter-label-bold">Range Date: <input type="date" class="mini-input"></div>
                    <div>
                        <span class="filter-label-bold">Status:</span>
                        <select class="status-select" x-model="filterStatus" @change="currentPage = 1">
                            <option value="All">▼ All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="search-wrapper">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search orders..." x-model="searchQuery" @input="currentPage = 1">
                </div>
            </div>

            <div class="box-container">
                <table class="main-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="order in paginatedOrders" :key="order.id">
                            <tr>
                                <td><a href="#" class="order-id-link" x-text="order.id" @click.prevent="openModal(order)"></a></td>
                                <td>
                                    <div x-show="!order.isEditing" class="customer-name" x-text="order.name"></div>
                                    <input x-show="order.isEditing" type="text" x-model="order.name" class="edit-input">
                                </td>
                                <td x-text="order.date"></td>
                                <td>
                                    <span x-show="!order.isEditing" class="status-text" :class="'status-'+order.status.toLowerCase()" x-text="order.status"></span>
                                    <select x-show="order.isEditing" x-model="order.status" class="edit-input">
                                        <option value="Pending">Pending</option>
                                        <option value="Shipped">Shipped</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </td>
                                <td>
                                    <div x-show="!order.isEditing" style="font-weight: 800; color: var(--text-dark);" x-text="order.total"></div>
                                    <input x-show="order.isEditing" type="text" x-model="order.total" class="edit-input">
                                </td>
                                <td>
                                    <div class="action-group">
                                        <button class="action-btn" @click="toggleEdit(order)">
                                            <svg x-show="!order.isEditing" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <svg x-show="order.isEditing" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button class="action-btn delete" @click="deleteOrder(order.id)">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <button class="pg-btn" @click="currentPage--" :disabled="currentPage === 1">Prev</button>
                    <template x-for="p in totalPages" :key="p">
                        <div class="pg-num" :class="currentPage === p ? 'active' : ''" x-text="p" @click="currentPage = p"></div>
                    </template>
                    <button class="pg-btn" @click="currentPage++" :disabled="currentPage === totalPages">Next</button>
                </div>
            </div>
        </div>

        <div class="box-container stats-box">
            <h2 class="stats-title">Order Statistics</h2>
            <div class="stat-row"><span class="stat-label">Shipped</span><span class="stat-val" style="color: #0284c7;" x-text="orders.filter(o => o.status === 'Shipped').length"></span></div>
            <div class="stat-row"><span class="stat-label">Pending</span><span class="stat-val" style="color: #ea580c;" x-text="orders.filter(o => o.status === 'Pending').length"></span></div>
            <div class="stat-row"><span class="stat-label">Delivered</span><span class="stat-val" style="color: #16a34a;" x-text="orders.filter(o => o.status === 'Delivered').length"></span></div>
            <div class="stat-row"><span class="stat-label">Cancelled</span><span class="stat-val" style="color: #dc2626;" x-text="orders.filter(o => o.status === 'Cancelled').length"></span></div>
            <div class="stat-row" style="margin-top: 10px; border-top: 2px solid #475569; padding-top: 15px; border-bottom: none;">
                <span class="stat-label" style="color: #1e293b; font-weight: 900;">Total</span>
                <span class="stat-val" x-text="orders.length"></span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderApp', () => ({
                filterStatus: 'All',
                searchQuery: '',
                currentPage: 1,
                itemsPerPage: 5,
                toastShow: false,
                toastMsg: '',
                currentTime: '',
                
                // Modal States Extension
                modalShow: false,
                isModalEditing: false, 
                selectedOrder: null,

                orders: [
                    { 
                        id: 'ORD-1001', name: 'Alice Dela Cruz', date: 'Oct 23, 2024', status: 'Pending', total: '₱1,200.00', subtotal: '₱1,100.00', shippingFee: '₱100.00', isEditing: false,
                        email: 'alice@gmail.com', phone: '0917-123-4567', address: '123 Rizal Ave, Brgy. Poblacion, Quezon City', paymentMethod: 'GCash', paymentStatus: 'Paid', txnId: 'TXN-9921083', trackingNum: 'PRNT-TRK-88192', deliveryNote: 'Awaiting production batch', adminNotes: 'Customer requested double packaging.',
                        items: [
                            { prodId: 'P1', title: 'Custom Comfort Tee (Black / M)', sku: 'PRNT-TSH-01', qty: 2, price: '₱550.00' }
                        ]
                    },
                    { 
                        id: 'ORD-1002', name: 'Bob Smith', date: 'Oct 22, 2024', status: 'Shipped', total: '₱850.50', subtotal: '₱750.50', shippingFee: '₱100.00', isEditing: false,
                        email: 'bob.smith@yahoo.com', phone: '0918-987-6543', address: '456 Oak Lane, Forbes Park, Makati City', paymentMethod: 'Credit Card', paymentStatus: 'Paid', txnId: 'TXN-4412095', trackingNum: 'PRNT-TRK-44102', deliveryNote: 'In transit via Printify Logistics', adminNotes: '',
                        items: [
                            { prodId: 'P2', title: 'Ceramic Accent Mug 11oz', sku: 'PRNT-MUG-02', qty: 1, price: '₱350.50' },
                            { prodId: 'P3', title: 'Minimalist Tote Bag', sku: 'PRNT-TOT-09', qty: 1, price: '₱400.00' }
                        ]
                    },
                    { 
                        id: 'ORD-1003', name: 'Charlie Kim', date: 'Oct 22, 2024', status: 'Delivered', total: '₱2,100.00', subtotal: '₱1,950.00', shippingFee: '₱150.00', isEditing: false,
                        email: 'charlie.k@outlook.com', phone: '0922-333-4444', address: '789 Lotus St, Juna Subdivision, Davao City', paymentMethod: 'Maya', paymentStatus: 'Paid', txnId: 'TXN-8812043', trackingNum: 'PRNT-TRK-10923', deliveryNote: 'Package received by customer', adminNotes: 'Delivered directly to building receptionist.',
                        items: [
                            { prodId: 'P4', title: 'Unisex Heavy Blend Hoodie (Gray / L)', sku: 'PRNT-HOD-88', qty: 1, price: '₱1,950.00' }
                        ]
                    },
                    { 
                        id: 'ORD-1004', name: 'David Luna', date: 'Oct 21, 2024', status: 'Cancelled', total: '₱1,500.00', subtotal: '₱1,400.00', shippingFee: '₱100.00', isEditing: false,
                        email: 'dluna@gmail.com', phone: '0905-555-1234', address: 'Block 4 Lot 2, Pinecrest Vill, Antipolo, Rizal', paymentMethod: 'Cash on Delivery', paymentStatus: 'Unpaid', txnId: 'N/A', trackingNum: '', deliveryNote: 'Order voided', adminNotes: 'Cancelled due to change of mind before production.',
                        items: [
                            { prodId: 'P5', title: 'A5 Hardcover Journal', sku: 'PRNT-JRN-12', qty: 2, price: '₱700.00' }
                        ]
                    },
                    { 
                        id: 'ORD-1005', name: 'Eve Mercado', date: 'Oct 21, 2024', status: 'Pending', total: '₱950.00', subtotal: '₱850.00', shippingFee: '₱100.00', isEditing: false,
                        email: 'eve_mercado@gmail.com', phone: '0947-888-9999', address: '14b Regency Tower, Ermita, Manila', paymentMethod: 'GCash', paymentStatus: 'Paid', txnId: 'TXN-1102943', trackingNum: '', deliveryNote: 'Awaiting production batch', adminNotes: '',
                        items: [
                            { prodId: 'P6', title: 'Embroidered Baseball Cap', sku: 'PRNT-CAP-04', qty: 1, price: '₱850.00' }
                        ]
                    }
                ],
                updateCurrentTime() {
                    const now = new Date();
                    const options = { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
                    this.currentTime = now.toLocaleDateString('en-US', options).replace(' at', ' |');
                },
                get filteredOrders() {
                    let filtered = this.orders;
                    if (this.filterStatus !== 'All') filtered = filtered.filter(o => o.status === this.filterStatus);
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(o => o.id.toLowerCase().includes(q) || o.name.toLowerCase().includes(q));
                    }
                    return filtered;
                },
                get totalPages() { return Math.ceil(this.filteredOrders.length / this.itemsPerPage) || 1; },
                get paginatedOrders() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    return this.filteredOrders.slice(start, start + this.itemsPerPage);
                },
                resetFilters() { this.filterStatus = 'All'; this.searchQuery = ''; this.currentPage = 1; },
                
                toggleEdit(order) {
                    if (order.isEditing) this.showToast("Order entry updated successfully!");
                    order.isEditing = !order.isEditing;
                },
                deleteOrder(id) {
                    if (confirm('Delete order ' + id + '?')) {
                        this.orders = this.orders.filter(o => o.id !== id);
                        this.showToast('Order deleted successfully');
                    }
                },
                showToast(msg) {
                    this.toastMsg = msg; this.toastShow = true;
                    setTimeout(() => this.toastShow = false, 3000);
                },

                // Advanced Pop-up Management
                openModal(order) {
                    // Gumawa ng raw deep copy para maiwasan ang auto-mutating data nang hindi sinasadya
                    this.selectedOrder = JSON.parse(JSON.stringify(order));
                    this.isModalEditing = false;
                    this.modalShow = true;
                },
                toggleModalEdit() {
                    if (this.isModalEditing) {
                        // Maghanap sa orihinal na array at i-update ang reference entry
                        const idx = this.orders.findIndex(o => o.id === this.selectedOrder.id);
                        if (idx !== -1) {
                            this.orders[idx] = JSON.parse(JSON.stringify(this.selectedOrder));
                        }
                        this.showToast("Order details saved into register successfully!");
                    }
                    this.isModalEditing = !this.isModalEditing;
                },
                recalculateOrderTotals() {
                    // Dynamic internal parsing para sa realtime computation ng admin matrix inputs
                    let calculatedSubtotal = 0;
                    this.selectedOrder.items.forEach(item => {
                        // Alisin ang currency symbol para sa malinis na parsing
                        let priceNum = parseFloat(String(item.price).replace(/[^0-9.]/g, '')) || 0;
                        calculatedSubtotal += (priceNum * (parseInt(item.qty) || 0));
                    });
                    
                    let shippingNum = parseFloat(String(this.selectedOrder.shippingFee).replace(/[^0-9.]/g, '')) || 0;
                    let calculatedTotal = calculatedSubtotal + shippingNum;
                    
                    // I-format pabalik gamit ang orihinal na local currency format setup
                    this.selectedOrder.subtotal = '₱' + calculatedSubtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    this.selectedOrder.total = '₱' + calculatedTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },
                closeModal() {
                    this.modalShow = false;
                    this.isModalEditing = false;
                    setTimeout(() => { this.selectedOrder = null; }, 200);
                }
            }))
        });
    </script>
</div>